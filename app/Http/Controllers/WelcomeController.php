<?php

namespace App\Http\Controllers;

use App\DriftersWormholeSystems;
use App\Models\DriftersWormholeReport;
use App\NeedWatchSystemMap;
use Illuminate\Http\Request;
use Symfony\Component\Routing\Annotation\Route;

class WelcomeController extends Controller
{
    /**
     * @Route("/report", name="report", methods={"POST"})
     * @param Request $request
     */
    public function report(Request $request)
    {
        $data = $request->validate([
            'system' => [
                'required',
                'array',
                function ($attribute, $value, $fail) {
                    $map = NeedWatchSystemMap::getMap();
                    $region = $value[0];
                    $system = $value[1];
                    if (!isset($map[$region]) || !in_array($system, $map[$region])) {
                        $fail($attribute . ' is invalid.');
                    }
                }
            ],
            'submitter' => 'required|string|max:40',
            'signatureName' => [
                'required',
                'string',
                function ($attribute, $value, $fail) {
                    if (!in_array($value ,DriftersWormholeSystems::SIGNATURE_LIST)) {
                        if ($value === '非流浪洞') {
                            return;
                        }
                        $fail($attribute . ' is invalid.');
                    }
                }
            ],
            'notice' => 'string|nullable|max:50',
        ]);

        DriftersWormholeReport::create([
            'system' => $data['system'][1],
            'submitter' => $data['submitter'],
            'signature_name' => $data['signatureName'],
            'notice' => $data['notice'] ?? '',
            'submitter_ip' => $request->getClientIp(),
        ]);

        return ['status' => 'ok'];
    }
}
