<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
* @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
* @license   http://framework.zend.com/license/new-bsd New BSD License
*/

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Model\User;
class BenutzertabelleController extends AbstractActionController
{
	public function benutzertabelleAction()
	{
		
		echo "<tr>";
		echo "<td>". $row['u_id'] . "</td>";
		echo "<td>". $row['username'] . "</td>";
		echo "<td>". $row['vorname'] . "</td>";
		echo "<td>". $row['nachname'] . "</td>";
		echo "<td>". $row['passwort'] . "</td>";
		echo "<td>". $row['email'] . "</td>";
		echo "<td>". $row['deaktiviert'] . "</td>";
		echo "<td>". $row['systemadmin'] . "</td>";


		return new ViewModel();

	}
}