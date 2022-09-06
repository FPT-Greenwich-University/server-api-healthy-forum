<?php
declare(strict_types=1);

namespace App\Services\Chat;

use App\Http\Requests\Api\Chats\CreateChatRoomRequest;
use App\Http\Requests\Api\Chats\StoreMessageRequest;
use App\Models\User;

interface ChatServiceContracts
{
    /**
     * Send the message to another user
     *
     * @param int $chatRoomId
     * @param StoreMessageRequest $request
     * @return bool <p>Return <b>True</b> if store new message success, otherwise <b>False</b>
     */
    public function sendMessage(int $chatRoomId, StoreMessageRequest $request): bool;

    public function downloadFileAsAZip($file): string;

    public function downloadAllFileAsAZip($files): string;

    public function createChatRoom(User $currentUser, User $targetUser): bool;
}
