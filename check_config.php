<?php

echo "Broadcasting Driver: " . config('broadcasting.default') . "\n";
echo "Pusher App ID: " . config('broadcasting.connections.pusher.app_id') . "\n";
echo "Pusher App Key: " . config('broadcasting.connections.pusher.key') . "\n";
echo "Pusher Host: " . config('broadcasting.connections.pusher.options.host') . "\n";
