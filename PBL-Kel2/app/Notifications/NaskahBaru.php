<?php

namespace App\Notifications;

use App\Models\Naskah;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NaskahBaru extends Notification implements ShouldQueue
{
    use Queueable;

    protected $naskah;

    /**
     * Create a new notification instance.
     */
    public function __construct(Naskah $naskah)
    {
        $this->naskah = $naskah;
    }

    /**
     * Get the notification's delivery channels.
     */
    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Naskah Baru Perlu Direview')
            ->greeting('Halo Admin,')
            ->line('Ada naskah baru yang perlu direview.')
            ->line('**Detail Naskah:**')
            ->line('Judul: ' . $this->naskah->judul)
            ->line('Pengirim: ' . $this->naskah->pengirim->name)
            ->line('Email Pengirim: ' . $this->naskah->pengirim->email)
            ->line('Tanggal Dikirim: ' . $this->naskah->created_at->format('d M Y H:i'))
            ->line('Batas Waktu: ' . $this->naskah->batas_waktu->format('d M Y'))
            ->when($this->naskah->deskripsi, function ($message) {
                return $message->line('**Deskripsi:**')
                              ->line($this->naskah->deskripsi);
            })
            ->action('Review Naskah', route('admin.naskah.show', $this->naskah->id))
            ->line('Silakan review naskah ini sesegera mungkin.')
            ->salutation('Sistem Manajemen Naskah');
    }

    /**
     * Get the database representation of the notification.
     */
    public function toDatabase(object $notifiable): array
    {
        return [
            'type' => 'naskah_baru',
            'title' => 'Naskah Baru',
            'message' => "Naskah baru '{$this->naskah->judul}' dari {$this->naskah->pengirim->name}",
            'naskah_id' => $this->naskah->id,
            'naskah_judul' => $this->naskah->judul,
            'pengirim_name' => $this->naskah->pengirim->name,
            'pengirim_email' => $this->naskah->pengirim->email,
            'batas_waktu' => $this->naskah->batas_waktu,
            'created_at' => $this->naskah->created_at,
            'action_url' => route('admin.naskah.show', $this->naskah->id),
            'icon' => 'fas fa-file-plus',
            'color' => 'info'
        ];
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray(object $notifiable): array
    {
        return $this->toDatabase($notifiable);
    }
}
