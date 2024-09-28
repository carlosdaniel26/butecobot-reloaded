<?php

namespace App\SlashCommands\Traits\Events;

use Laracord\Commands\SlashCommand;
use App\Repositories\EventRepository;

/**
 * @mixin SlashCommand
 */
trait CreateEvents
{
    /**
     * Handle the slash command.
     *
     * @param  \Discord\Parts\Interactions\Interaction  $interaction
     * @return mixed
     */
    public function createEvent($interaction): void
    {
        $eventRepository = new EventRepository($this);
        $discordId = $interaction->member->user->id;
        $eventName = $this->value('criar.nome');
        $optionA = $this->value('criar.a');
        $optionB = $this->value('criar.b');

        $event = $eventRepository->create(strtoupper($eventName), $optionA, $optionB, $discordId);

        if (!$event) {
            $interaction->respondWithMessage(
                $this->message('Ocorreu um erro ao criar o evento!')
                    ->title('Eventos')
                    ->build(),
                true
            );
            return;
        }

        $interaction->respondWithMessage(
            $this->message(sprintf(
                    "Evento criado com sucesso!\n\n**[#%s] %s**\nA: %s\nB: %s",
                    $event['id'],
                    $eventName,
                    $optionA,
                    $optionB
                ))
                ->title('Eventos')
                ->build(),
            true
        );
    }
}
