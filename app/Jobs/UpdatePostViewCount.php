<?php

namespace App\Jobs;

use App\Models\Post;
use App\Repositories\Interfaces\IPostRepository;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class UpdatePostViewCount implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $postId;
//    private readonly IPostRepository $postRepository;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(int $postId)
    {
        $this->postId= $postId;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // TODO :: add service increse post view
        Post::where("id", $this->postId)->increment("total_view");
    }

}
