<?php

namespace App\Http\Controllers;

use App\Http\Resources\DriftersWormholeReportResource;
use App\Models\DriftersWormholeReport;
use App\NeedWatchSystemMap;
use Carbon\Carbon;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\{Collection, Facades\Validator,};
use Illuminate\Validation\ValidationException;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return Renderable
     */
    public function index()
    {
        return view('home');
    }

    /**
     * @Route("/drifters-wormhole-state", name="drifters-wormhole-state", methods={"GET"})
     */
    public function getDriftersWormholeState()
    {
        $resp = [];
        $time = Carbon::now()->subDays(2);
        $reports = DriftersWormholeReport::where('created_at', '>', $time->__toString())->get();
        collect(NeedWatchSystemMap::getMap())->each(function (array $systems, string $region) use (&$resp, $reports) {
            foreach ($systems as $system) {
                $row = [
                    'region' => $region,
                    'system' => $system,
                    'info' => '',
                ];

                $reports->where('system', $system)->whenNotEmpty(function (Collection $collection) use (&$row) {
                    $var = $collection->sortByDesc('created_at')->first->get();
                    $row['info'] = [
                        'signature_name' => $var->signature_name,
                        'submitter' => $var->submitter,
                        'time' => $var->created_at->__toString(),
                    ];
                });
                $resp[] = $row;
            }
        });
        return $resp;
    }

    /**
     * @Route("/system-history/{systemName}", name="system-history", methods={"GET"})
     * @param string $systemName
     * @return JsonResource
     * @throws ValidationException
     */
    public function getSystemHistory(string $systemName)
    {
        Validator::make(['systemName' => $systemName], [
            'systemName' => [
                'required',
                'string',
                function ($attribute, $value, $fail) {
                    $map = NeedWatchSystemMap::getSystemList();
                    if (!in_array($value, $map)) {
                        $fail($attribute . ' is invalid.');
                    }
                },
            ]
        ])->validate();

        return DriftersWormholeReportResource::collection(DriftersWormholeReport::whereSystem($systemName)->get());
    }

    /**
     * @Route("/report/delete/{id}", name="delete-report", methods={"POST"})
     * @param int $id
     */
    public function deleteReport(int $id)
    {
        $report = DriftersWormholeReport::findOrFail($id);
        $report->delete();
        return ['status' => 'ok'];
    }
}
