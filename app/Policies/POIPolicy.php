<?php

namespace TravelCompanion\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use TravelCompanion\POI;
use TravelCompanion\User;

class POIPolicy
{
	use HandlesAuthorization;

	/**
	 * Determine whether the user can view any p o i s.
	 *
	 * @param  \TravelCompanion\User  $user
	 * @return mixed
	 */
	public function viewAny(User $user)
	{
		//
	}

	/**
	 * Determine whether the user can view the p o i.
	 *
	 * @param  \TravelCompanion\User  $user
	 * @param  \TravelCompanion\POI  $pOI
	 * @return mixed
	 */
	public function view(User $user, POI $pOI)
	{
		//
	}

	/**
	 * Determine whether the user can create p o i s.
	 *
	 * @param  \TravelCompanion\User  $user
	 * @return mixed
	 */
	public function create(User $user)
	{
		//
	}

	/**
	 * Determine whether the user can update the p o i.
	 *
	 * @param  \TravelCompanion\User  $user
	 * @param  \TravelCompanion\POI  $pOI
	 * @return mixed
	 */
	public function update(User $user, POI $pOI)
	{
		//
	}

	/**
	 * Determine whether the user can delete the p o i.
	 *
	 * @param  \TravelCompanion\User  $user
	 * @param  \TravelCompanion\POI  $pOI
	 * @return mixed
	 */
	public function delete(User $user, POI $pOI)
	{
		//
	}

	/**
	 * Determine whether the user can restore the p o i.
	 *
	 * @param  \TravelCompanion\User  $user
	 * @param  \TravelCompanion\POI  $pOI
	 * @return mixed
	 */
	public function restore(User $user, POI $pOI)
	{
		//
	}

	/**
	 * Determine whether the user can permanently delete the p o i.
	 *
	 * @param  \TravelCompanion\User  $user
	 * @param  \TravelCompanion\POI  $pOI
	 * @return mixed
	 */
	public function forceDelete(User $user, POI $pOI)
	{
		//
	}
}
