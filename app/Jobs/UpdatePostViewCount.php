<?php

namespace App\Jobs;

use App\Services\PostServices\PostServiceInterface;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class UpdatePostViewCount implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;


    /**
     * The post instance
     *
     * @var integer
     */
    protected int $postId;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(int $postId)
    {
        $this->postId = $postId;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(PostServiceInterface $postService)
    {
        // Update the total view of the post
        $postService->updatePostView($this->postId);
    }
}