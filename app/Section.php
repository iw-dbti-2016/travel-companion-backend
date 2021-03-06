<?php

namespace Columbo;

use Columbo\Action;
use Columbo\Casts\WeatherIcon;
use Columbo\Interfaces\TrackedByActions;
use Columbo\Location;
use Columbo\POI;
use Columbo\Report;
use Columbo\Traits\Visibility;
use Columbo\Trip;
use Columbo\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

class Section extends Model implements TrackedByActions
{
    use SoftDeletes, Visibility;

    public function __construct(array $attributes = [])
	{
	    $this->setRawAttributes([
	    	"published_at" => Carbon::now(),
	    ], true);

	    parent::__construct($attributes);
	}

    protected $fillable = [
        "content",
        "image",
        "image_caption",
        "start_time",
        "end_time",
        "weather_icon",
        "temperature",
        "is_draft",
        "visibility",
        "published_at",
    ];

    protected $visible = [
        "id",
        "is_draft",
        "content",
        "image",
        "image_caption",
        "start_time",
        "end_time",
        "weather_icon",
        "temperature",
        "visibility", // Sometimes???
        "published_at",
        "created_at",
        "updated_at",
        "owner",
    ];

    protected $casts = [
        "published_at" => "datetime:d/m/Y H:i",
        "is_draft" => "boolean",
        "weather_icon" => WeatherIcon::class,
    ];

    protected $appends = [
        "published_at_diff",
        "duration_formatted",
    ];

    public function scopeNoDraft($query)
    {
        return $query->whereIsDraft(false);
    }

    public function scopeOrderRecent($query)
    {
        return $query->orderBy("published_at", "desc");
    }

    public function scopePublished($query)
    {
        return $query->where("published_at", "<", Carbon::now("UTC"));
    }

    public function getStartTimeAttribute($start_time)
    {
        return ($start_time == null) ? $start_time : substr($start_time, 0, 5);
    }

    public function getEndTimeAttribute($end_time)
    {
        return ($end_time == null) ? $end_time : substr($end_time, 0, 5);
    }

    public function owner()
    {
    	return $this->belongsTo(User::class, 'user_id');
    }

    public function report()
    {
    	return $this->belongsTo(Report::class);
    }

    public function locations()
    {
    	return $this->morphedByMany(Location::class, 'section_locationable');
    }

    public function pois()
    {
    	return $this->morphedByMany(POI::class, 'section_locationable');
    }

    public function actions()
    {
        return $this->morphMany(Action::class, 'actionable');
    }
}
