<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * 代表流浪洞的报告。
 *
 * @package App\Models
 * @property int $id
 * @property string $system
 * @property string $signature_name
 * @property string $submitter
 * @property string $submitter_ip
 * @property string $notice
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static Builder|DriftersWormholeReport newModelQuery()
 * @method static Builder|DriftersWormholeReport newQuery()
 * @method static Builder|DriftersWormholeReport query()
 * @method static Builder|DriftersWormholeReport whereCreatedAt($value)
 * @method static Builder|DriftersWormholeReport whereId($value)
 * @method static Builder|DriftersWormholeReport whereNotice($value)
 * @method static Builder|DriftersWormholeReport whereSignatureName($value)
 * @method static Builder|DriftersWormholeReport whereSubmitter($value)
 * @method static Builder|DriftersWormholeReport whereSystem($value)
 * @method static Builder|DriftersWormholeReport whereSubmitterIp($value)
 * @method static Builder|DriftersWormholeReport whereUpdatedAt($value)
 * @mixin Eloquent
 */
class DriftersWormholeReport extends Model
{
    use HasFactory;

    protected $fillable = ['system', 'signature_name', 'submitter', 'notice', 'submitter_ip'];
}
