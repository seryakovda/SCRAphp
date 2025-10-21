<?php

namespace DB\View;


use \DB\Connection;

class View_Users extends \DB\SQL\View\View_Users
{
	const login =  'login';

	const password_crypto =  'password_crypto';

	const id =  'id';

	const id_human =  'id_human';

	const session_id =  'session_id';

	const superUser =  'superUser';

	const admin =  'admin';

	const uploadDMz =  'uploadDMz';

	const renewPassword =  'renewPassword';

	const del =  'del';

	const runDefaultScript =  'runDefaultScript';

	const surname =  'surname';

	const name =  'name';

	const patronName =  'patronName';

}
