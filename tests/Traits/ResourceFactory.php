<?php

namespace Tests\Traits;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\Crypt;
use TravelCompanion\Location;
use TravelCompanion\Report;
use TravelCompanion\Section;
use TravelCompanion\Trip;
use TravelCompanion\User;
use Tymon\JWTAuth\Facades\JWTAuth;

trait ResourceFactory
{
	/**
	 * Creates a user. If data is passed, it is used.
	 *
	 * @param  array  $data
	 * @return TravelCompanion\User
	 */
	protected function createUser($data=[])
	{
		return factory(User::class)->create($data);
	}

	/**
	 * Creates a trip. If data is passed, it is used
	 * 	and if no user is passed, it is created.
	 *
	 * @param  TravelCompanion\User|null $user
	 * @param  array     $data
	 * @return TravelCompanion\Trip
	 */
	protected function createTrip(User $user=null, $data=[])
	{
		if ($user == null) $user = $this->createUser();

		return $user->tripsOwner()->save(factory(Trip::class)->make($data));
	}

	/**
	 * Creates a report. If data is passed, it is used
	 * 	and if no trip is passed, it is created using
	 * 	the user given as first parameter or a new
	 * 	created user.
	 *
	 * @param  TravelCompanion\User|null $user
	 * @param  TravelCompanion\Trip|null $trip
	 * @param  array     $data
	 * @return TravelCompanion\Report
	 */
	protected function createReport(User $user=null, Trip $trip=null, $data=[])
	{
		if ($user == null) $user = $this->createUser();
		if ($trip == null) $trip = $this->createTrip($user);

		$report = factory(Report::class)->make($data);
        $report->owner()->associate($user);
        $report->trip()->associate($trip);
        $report->save();

		return $report;
	}

	/**
	 * Creates a section. If data is passed, it is used
	 * 	and if no report is passed it is created using
	 * 	the used given as first parameter or a new
	 * 	created user.
	 *
	 * @param  TravelCompanion\User|null   $user
	 * @param  TravelCompanion\Report|null $report
	 * @param  array       $data
	 * @return TravelCompanion\Section
	 */
	protected function createSection(User $user=null, Report $report=null, $data=[])
	{
		if ($user   == null) $user 	 = $this->createUser();
		if ($report == null) $report = $this->createReport($user);

		$section = factory(Section::class)->make($data);
        $section->owner()->associate($user);
        $section->report()->associate($report);
        $section->save();

		return $section;
	}

	/**
	 * Creats a location. If data is passed, it is used
	 * 	and if no user is passed it is created. If a
	 * 	locationReferencer is passed, the location is
	 * 	associated to that referencer.
	 *
	 * @param  TravelCompanion\User|null   $user
	 * @param  Location/POI				   $locationReferencer
	 * @return TravelCompanion\Location
	 */
	protected function createLocation(User $user=null, $locationReferencer=null, $data=[])
	{
		if ($user == null) $user = $this->createUser();

		$location = $user->locations()->save(factory(Location::class)->make($data));

		if ($locationReferencer != null) {
			$locationReferencer->locationable()->associate($location);
			$locationReferencer->save();
		}

		return $location;
	}
}