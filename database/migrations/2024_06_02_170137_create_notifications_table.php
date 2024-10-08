<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNotificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // The user who receives the notification
            $table->foreignId('publication_id')->nullable()->constrained()->onDelete('cascade'); // Link to the publication
            $table->foreignId('vote_id')->nullable()->constrained()->onDelete('cascade'); // Link to the vote (if applicable)
            $table->foreignId('comment_id')->nullable()->constrained()->onDelete('cascade'); // Link to the comment (if applicable)
            $table->enum('type', [1, 2, 3]); // 1: upvote, 2: downvote, 3: comment

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('notifications');
    }
}
