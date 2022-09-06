<?php
declare(strict_types=1);

namespace App\Services\Chat;

use App\Events\ChatRoomCreated;
use App\Events\MessageSent;
use App\Http\Requests\Api\Chats\CreateChatRoomRequest;
use App\Http\Requests\Api\Chats\StoreMessageRequest;
use App\Models\User;
use App\Repositories\Interfaces\IChatRoomDetailRepository;
use App\Repositories\Interfaces\IChatRoomRepository;
use App\Repositories\Interfaces\IMessageRepository;
use App\Repositories\Interfaces\IPermissionRepository;
use App\Repositories\Interfaces\IUserRepository;
use App\Services\FileServices\FileServicesContract;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use ZipArchive;

class ChatServices implements ChatServiceContracts
{
    private readonly IMessageRepository $messageRepository;
    private readonly FileServicesContract $fileServices;
    private readonly IChatRoomRepository $chatRoomRepository;
    private readonly IUserRepository $userRepository;
    private readonly IPermissionRepository $permissionRepository;
    private readonly IChatRoomDetailRepository $chatRoomDetailRepository;

    public function __construct(
        IMessageRepository        $messageRepository,
        FileServicesContract      $fileServicesContract,
        IChatRoomRepository       $chatRoomRepository,
        IUserRepository           $userRepository,
        IPermissionRepository     $permissionRepository,
        IChatRoomDetailRepository $chatRoomDetailRepository,
    )
    {
        $this->messageRepository = $messageRepository;
        $this->fileServices = $fileServicesContract;
        $this->chatRoomRepository = $chatRoomRepository;
        $this->userRepository = $userRepository;
        $this->permissionRepository = $permissionRepository;
        $this->chatRoomDetailRepository = $chatRoomDetailRepository;

    }

    public function sendMessage(int $chatRoomId, StoreMessageRequest $request): bool
    {
        $user = $request->user(); // Get the current auth user
        $targetUserId = (int)($request->input('targetUserId')); // Get the target user retrieve message
        $array = []; // Array for collect file input from user

        DB::beginTransaction();

        try {
            // Store new message in database
            $message = $this->messageRepository->createNewMessage([
                'chat_room_id' => $chatRoomId,
                'source_id' => $user->id,
                'target_id' => $targetUserId,
                'message' => trim($request->input('message'))
            ]);

            // Send message include the files if request has files
            if ($request->hasFile("files")) {
                foreach ($request->file("files") as $key => $file) {
                    $originFileName = $file->getClientOriginalName();
                    $hashFileName = $file->hashName(); // Hash file's name
                    $targetDir = 'messages/files/'; // Set default target directory
                    $this->fileServices->storeFile($file, $targetDir, $hashFileName);

                    $array[$key]['name'] = $originFileName;
                    $array[$key]['path'] = $targetDir . $hashFileName;
                }

                // Insert data to File table with relationship
                $message->files()->createMany($array);
            }

            DB::commit();

            broadcast(new MessageSent($user, $message, $chatRoomId))->toOthers();

        } catch (Exception $exception) {
            Log::error($exception->getMessage());
            DB::rollBack();

            return false;
        }

        return true;
    }

    /**
     * Download single file message chat
     *
     * @param $file
     * @return string
     */
    public function downloadFileAsAZip($file): string
    {
        $zip = new ZipArchive(); // create object of zip archive

        $zipFile = 'messageZipFile.zip'; // Set the default name of file zip

        // Open the file zip
        $zip->open($zipFile, ZipArchive::CREATE | ZipArchive::OVERWRITE);
        $zip->addFile(public_path() . '/' . $file->path, $file->name);
        $zip->close(); // Close file zip

        return $zipFile;
    }

    public function downloadAllFileAsAZip($files): string
    {
        $zip = new ZipArchive(); // Create new object of ZipArchive class

        $zipFile = 'messageZipFile.zip'; // Set the default name of file zip


        // Open the file zip
        if ($zip->open($zipFile, ZipArchive::CREATE | ZipArchive::OVERWRITE) === TRUE) {
            foreach ($files as $key => $file) {
                $zip->addFile(public_path() . '/' . $file['path'], $file['name']); // Add each the file to the zip file
            }

            $zip->close(); // Close file zip
        }

        return $zipFile;
    }

    public function createChatRoom(User $currentUser, User $targetUser): bool
    {
        DB::beginTransaction();

        try {
            $newChatRoom = $this->chatRoomRepository->createNewRoom(); // Create new chat room for both of two users

            // Create new chat room details
            $this->chatRoomDetailRepository->createChatRoomDetails(chatRoom: $newChatRoom, currentUser: $currentUser, targetUser: $targetUser);

            // Store new message to the database
            $this->messageRepository->createNewMessage([
                'message' => 'Hello',
                'chat_room_id' => $newChatRoom->id,
                'source_id' => $currentUser->id,
                'target_id' => $targetUser->id
            ]);

            $permissionName = 'chat-room.' . $newChatRoom->id; // Initial permission name
            $this->permissionRepository->create(['name' => $permissionName, 'guard_name' => 'web']); // Create new permission with permission name

            $this->userRepository->setDirectPermission($currentUser->id, $permissionName); // Give permission access this room to source user
            $this->userRepository->setDirectPermission($targetUser->id, $permissionName); // Giver permission access this room to target user
            DB::commit();

            broadcast(new ChatRoomCreated($targetUser->id)); // Send broadcast notification to VueJS client side
        } catch (Exception $exception) {
            Log::error($exception->getMessage());
            DB::rollBack();

            return false;
        }
        return true;
    }
}
