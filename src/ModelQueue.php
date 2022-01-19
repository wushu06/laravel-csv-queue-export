<?php

namespace Nour\Export;

use Closure;
use Illuminate\Support\Facades\Bus;
use Nour\Export\Interfaces\Pipe;
use Nour\Export\Jobs\NotifyUser;

class ModelQueue implements Pipe
{
    /**
     * @param $export
     * @param Closure $next
     * @return mixed
     */
    public function handle($export, Closure $next)
    {
        $claimsJobs = $export->claimsJobs;
        try {
            Bus::batch($claimsJobs)->then(function () use ($export) {
                $this->sendEmail('Claim export has finished!', $export->email);
            })->catch(function () use ($export) {
                $this->sendEmail('Claim export has failed!', $export->email);
            })->dispatch();
        } catch (\Throwable $e) {
            logger($e->getMessage());
        }
        return $next($claimsJobs);
    }

    /**
     * @param $message
     * @param $email
     */
    private function sendEmail($message, $email): void
    {
        if ($email) {
            dispatch(new NotifyUser($message, $email));
        }
    }

}
