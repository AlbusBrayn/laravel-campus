<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging\CloudMessage;

class FirebaseService
{

    public static function connect(): \Kreait\Firebase\Contract\Database
    {
        $firebase = (new Factory)
            ->withServiceAccount(base_path(getenv('FIREBASE_CREDENTIALS')))
            ->withDatabaseUri(getenv('FIREBASE_DATABASE_URL'));

        return $firebase->createDatabase();
    }

    public static function connectStorage(): \Kreait\Firebase\Contract\Storage
    {
        $firebase = (new Factory)
            ->withServiceAccount(base_path(getenv('FIREBASE_CREDENTIALS')))
            ->withDatabaseUri(getenv('FIREBASE_DATABASE_URL'));

        return $firebase->createStorage();
    }

    /**
     * @param string $deviceId
     * @param string $title
     * @param string $description
     * @param array $extraData
     * @return void
     * @throws \Kreait\Firebase\Exception\FirebaseException
     * @throws \Kreait\Firebase\Exception\MessagingException
     */
    public static function sendNotification(string $deviceId, string $title, string $description, array $extraData = []): void
    {
        try {
            $firebase = (new Factory)
                ->withServiceAccount(base_path(getenv('FIREBASE_CREDENTIALS')))
                ->withDatabaseUri(getenv('FIREBASE_DATABASE_URL'));

            $messaging = $firebase->createMessaging();

            $message = CloudMessage::withTarget('token', $deviceId)
                ->withNotification([
                    'title' => $title,
                    'body' => $description,
                ])
                ->withData($extraData);

            $messaging->send($message);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
        }
    }
}
