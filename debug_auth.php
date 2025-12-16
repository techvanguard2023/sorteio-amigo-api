<?php

use App\Models\Participant;
use App\Models\WishlistItem;

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$participantId = 5;
$wishlistId = 4;

echo "--- DEBUG START ---\n";

$item = WishlistItem::find($wishlistId);
if (!$item) {
    echo "WishlistItem $wishlistId not found.\n";
} else {
    echo "WishlistItem ID: " . $item->id . "\n";
    echo "WishlistItem Participant ID: " . $item->participant_id . "\n";
    
    $participant = $item->participant;
    if (!$participant) {
        echo "Participant not found via relationship.\n";
    } else {
        echo "Participant ID: " . $participant->id . "\n";
        echo "Participant User ID: " . $participant->user_id . "\n";
        echo "Participant Group ID: " . $participant->group_id . "\n";
        
        $group = $participant->group;
        if (!$group) {
            echo "Group not found via relationship.\n";
        } else {
            echo "Group ID: " . $group->id . "\n";
            echo "Group Organizer ID: " . $group->organizer_id . "\n";
        }
    }
}

echo "--- DEBUG END ---\n";
