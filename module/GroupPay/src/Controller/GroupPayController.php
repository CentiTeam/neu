namespace GroupPay\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class GroupPayController extends AbstractActionController
{
	public function indexAction()
	{
	     return new ViewModel();
	
	}

	public function overviewAction()
	{
	}
}