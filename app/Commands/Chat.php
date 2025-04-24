<?php

namespace App\Commands;

use App\Enums\ChatBotEnum;
use App\Enums\EmbedColorsEnum;
use Discord\Builders\MessageBuilder;
use Discord\Parts\Embed\Embed;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Laracord\Commands\Command;

class Chat extends Command
{
    /**
     * The command name.
     *
     * @var string
     */
    protected $name = 'chat';

    /**
     * The command description.
     *
     * @var string|null
     */
    protected $description = 'Чат с ИИ-шкой иожет кому че нить надо будет.';

    /**
     * The command usage.
     *
     * @var string
     */
    protected $usage = '<message>';

    /**
     * The OpenAI system prompt.
     */
    protected string $prompt = 'You are a smart and helpful AI assistant. You dont answer in too much detail but always to the point without any extraneous information.';

    /**
     * Handle the command.
     *
     * @param  \Discord\Parts\Channel\Message  $message
     * @param  array  $args
     * @return mixed
     */
    public function handle($message, $args)
    {
        $input = trim(implode(' ', $args ?? []));

        if (!$input) {
            return $this
                ->message(ChatBotEnum::NO_INPUT->value)
                ->title('Chat')
                ->error()
                ->reply($message);
        }

        $message->channel->broadcastTyping()->then(function () use ($message, $input) {
            $key = "{$message->channel->id}.chat.responses";
            $input = Str::limit($input, 384);

            $messages = cache()->get($key, [['role' => 'system', 'content' => $this->prompt]]);
            $messages[] = ['role' => 'user', 'content' => $input];

            try {
                $response = $this->makeOpenRouterRequest($messages);

                if (!isset($response['choices'][0]['message']['content'])) {
                    throw new \RuntimeException('Invalid API response structure');
                }

                $responseContent = $response['choices'][0]['message']['content'];

                $messages[] = ['role' => 'assistant', 'content' => $responseContent];
                cache()->put($key, $messages, now()->addMinute());

                return $this
                    ->message($responseContent)
                    ->reply($message);


            } catch (\Exception $e) {
                \Log::error('Chat command error: ' . $e->getMessage());

                return $this
                    ->message('Ошибка: ' . $e->getMessage())
                    ->error()
                    ->send($message);
            }
        });
    }

    /**
     * Make request to OpenRouter API
     *
     * @param  array  $messages
     * @return array
     * @throws \Exception
     */
    protected function makeOpenRouterRequest(array $messages)
    {
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->apiKey(),
            'HTTP-Referer' => 'https://your-app.com',
            'X-Title' => 'Discord Bot',
            'Content-Type' => 'application/json',
        ])->post('https://openrouter.ai/api/v1/chat/completions', [
            'model' => 'qwen/qwen2.5-vl-32b-instruct:free',
            'messages' => $messages,
        ]);

        if ($response->failed()) {
            throw new \RuntimeException(
                "API Error: " . $response->status() . " - " . $response->body()
            );
        }

        return $response->json();
    }

    /**
     * Retrieve the API key
     *
     * @return string
     */
    protected function apiKey()
    {
        return config('laracord.qwen.key');
    }
}
