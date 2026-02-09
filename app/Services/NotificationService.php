<?php

namespace App\Services;

use App\Models\Notification;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use App\Mail\NotificationMail;

class NotificationService
{
    /**
     * Send notification with optional email
     */
    public static function send(
        int $userId,
        string $title,
        string $message,
        string $category = Notification::CATEGORY_SYSTEM,
        string $type = Notification::TYPE_INFO,
        ?string $link = null,
        ?array $data = null,
        bool $sendEmail = false
    ): Notification {
        $notification = Notification::create([
            'user_id' => $userId,
            'title' => $title,
            'message' => $message,
            'category' => $category,
            'type' => $type,
            'link' => $link,
            'data' => $data,
            'email_sent' => false,
        ]);

        // Send email for important notifications
        if ($sendEmail) {
            self::sendEmail($notification);
        }

        return $notification;
    }

    /**
     * Send transaction notification
     */
    public static function transactionNotify(
        int $userId,
        string $title,
        string $message,
        ?string $link = null,
        ?array $data = null,
        bool $sendEmail = true
    ): Notification {
        return self::send(
            $userId,
            $title,
            $message,
            Notification::CATEGORY_TRANSACTION,
            Notification::TYPE_SUCCESS,
            $link,
            $data,
            $sendEmail
        );
    }

    /**
     * Send promo notification
     */
    public static function promoNotify(
        int $userId,
        string $title,
        string $message,
        ?string $link = null,
        ?array $data = null
    ): Notification {
        return self::send(
            $userId,
            $title,
            $message,
            Notification::CATEGORY_PROMO,
            Notification::TYPE_INFO,
            $link,
            $data,
            false
        );
    }

    /**
     * Send follower notification
     */
    public static function followerNotify(
        int $userId,
        string $followerName,
        ?string $link = null
    ): Notification {
        return self::send(
            $userId,
            'Pengikut Baru!',
            "{$followerName} mulai mengikuti kamu bolo!",
            Notification::CATEGORY_FOLLOWER,
            Notification::TYPE_INFO,
            $link,
            null,
            false
        );
    }

    /**
     * Broadcast notification to multiple users
     */
    public static function broadcast(
        array $userIds,
        string $title,
        string $message,
        string $category = Notification::CATEGORY_SYSTEM,
        string $type = Notification::TYPE_INFO,
        ?string $link = null
    ): int {
        $count = 0;
        foreach ($userIds as $userId) {
            self::send($userId, $title, $message, $category, $type, $link);
            $count++;
        }
        return $count;
    }

    /**
     * Send email for notification
     */
    protected static function sendEmail(Notification $notification): void
    {
        try {
            $user = User::find($notification->user_id);
            if ($user && $user->email) {
                Mail::to($user->email)->queue(new NotificationMail($notification, $user));
                $notification->update(['email_sent' => true]);
            }
        } catch (\Exception $e) {
            // Log error but don't throw - email is secondary
            \Log::error('Failed to send notification email: ' . $e->getMessage());
        }
    }
}
