<?php
/**
 * @package       contentcli
 * @author        Alexandre ELISÉ <contact@alexandre-elise.fr>
 * @link          https://alexandre-elise.fr
 * @copyright (c) 2020 . Alexandre ELISÉ . Tous droits réservés.
 * @license       GPL-2.0-and-later GNU General Public License v2.0 or later
 * Created Date : 15/12/2020
 * Created Time : 11:34
 */

use Joomla\CMS\Application\CliApplication;
use Joomla\CMS\Factory;

define('CONTENTCLI_MINIMUM_PHP', '7.4.0');

if (version_compare(PHP_VERSION, CONTENTCLI_MINIMUM_PHP, '<'))
{
	die('Your host needs to use PHP ' . CONTENTCLI_MINIMUM_PHP . ' or higher to run this version of contentcli script');
}

// Set flag that this is a parent file.
const _JEXEC = 1;

error_reporting(E_ALL | E_NOTICE);
ini_set('display_errors', 1);

// Load system defines
if (file_exists(dirname(__DIR__) . '/defines.php'))
{
	require_once dirname(__DIR__) . '/defines.php';
}

if (!defined('_JDEFINES'))
{
	define('JPATH_BASE', dirname(__DIR__));
	require_once JPATH_BASE . '/includes/defines.php';
}

require_once JPATH_LIBRARIES . '/import.legacy.php';
require_once JPATH_LIBRARIES . '/cms.php';

// Load the configuration
require_once JPATH_CONFIGURATION . '/configuration.php';

if (!class_exists('ContentCli'))
{
	/**
	 * Class ContentCli
	 */
	class ContentCli extends CliApplication
	{
		/**
		 * How many articles to generate
		 * @var int $howManyArticles
		 * @since version
		 */
		private int $howManyArticles = 0;
		
		/**
		 * Method to run the application routines.  Most likely you will want to instantiate a controller
		 * and execute it, or perform some sort of task directly.
		 *
		 * @return  void
		 *
		 * @since       3.4 (CMS)
		 * @deprecated  4.0  The default concrete implementation of doExecute() will be removed, subclasses will need to provide their own implementation.
		 */
		protected function doExecute()
		{
			parent::doExecute();
			$this->out(date(DATE_RFC822) . ' Starting task');
			$this->out('Generate fake content for testing purpose');
			$this->out('How many articles to generate between (1 and 10000) ?');
			$this->setHowManyArticles($this->in());
			
			//actually generating articles
			$this->out(sprintf('Attempting to generate %d articles...', $this->getHowManyArticles()));
			try
			{
				$this->generateArticles();
			}
			catch (Throwable $exception)
			{
				$this->out($exception->getMessage());
				
				return;
			}
			$this->out(date(DATE_RFC822) . ' Task Done.');
			
		}
		
		/**
		 * @return int
		 */
		public function getHowManyArticles(): int
		{
			return $this->howManyArticles ?? 0;
		}
		
		/**
		 * @param   int  $howManyArticles
		 */
		public function setHowManyArticles(int $howManyArticles): void
		{
			if ($howManyArticles < 1 || $howManyArticles > 10000)
			{
				throw new OutOfBoundsException("Valid values are between 1 and 10000. Try again.", 500);
			}
			
			$this->howManyArticles = $howManyArticles;
		}
		
		
		/**
		 * Get 5 first paragraphs of lorem ipsum as fake text
		 * @return string
		 */
		private function getLoremIpsum(): string
		{
			return 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec eu convallis nulla. Aliquam in lectus sed mi congue consequat sit amet id arcu. Vestibulum libero turpis, malesuada sed sollicitudin sed, pellentesque ac lectus. Nullam non metus mauris. Pellentesque sit amet ex tristique magna rutrum bibendum. Aliquam non felis interdum, cursus libero eget, viverra ligula. Quisque ac mauris eu nisl fringilla ultrices.

Sed varius neque vel nulla efficitur, a porta libero aliquet. Pellentesque id urna eu felis ultrices luctus ut eget felis. Cras sed tellus finibus, pulvinar magna vestibulum, ullamcorper enim. Mauris augue mauris, interdum eu ultrices at, accumsan non sem. Vivamus non suscipit orci, in pulvinar neque. Suspendisse lobortis sagittis velit vitae congue. Phasellus dictum elit ut dui consectetur, eget egestas magna dignissim. Donec cursus in purus et ultricies. Aenean et sapien fringilla, faucibus diam faucibus, tincidunt ligula. Interdum et malesuada fames ac ante ipsum primis in faucibus. Nulla massa ex, finibus quis pharetra id, ullamcorper a massa.

Integer vitae iaculis urna. Cras vestibulum malesuada est, a cursus arcu malesuada sit amet. Fusce vel ipsum est. Donec accumsan sodales orci quis ullamcorper. Sed aliquam diam porttitor mi iaculis, sed luctus urna accumsan. Proin et tristique leo. Suspendisse vehicula malesuada justo, quis varius nisi tristique vitae. Nulla facilisi. Pellentesque sed est sit amet velit egestas tristique.

Aliquam a convallis turpis. Aliquam pharetra pulvinar enim, vel mattis ante tempor et. Ut velit mauris, aliquet at mattis et, tincidunt nec erat. Aenean rutrum, dolor sed consectetur iaculis, nisl dolor laoreet ligula, sed varius sapien justo ac orci. Nulla malesuada condimentum turpis, lobortis efficitur elit imperdiet in. Morbi non dictum mi, in rhoncus quam. Ut quam elit, aliquet sit amet fermentum pellentesque, aliquam sed quam. In eget scelerisque justo, sit amet cursus quam. Sed ullamcorper porta sapien, nec rhoncus quam facilisis ac. Curabitur fermentum congue dignissim.

Aenean consectetur pulvinar est, id vestibulum magna convallis quis. Nulla sed libero dapibus, euismod felis suscipit, aliquam ligula. Sed blandit urna ex, nec blandit ante bibendum vel. Quisque maximus malesuada arcu, nec dapibus turpis pretium a. Vivamus luctus, augue eu rhoncus interdum, metus felis malesuada orci, sit amet ullamcorper tellus odio nec mauris. Morbi aliquam enim eget ipsum molestie ullamcorper. Cras nec odio nisi. Vestibulum eget accumsan magna. Cras rhoncus convallis nibh eget interdum. Fusce suscipit eros ac urna sodales aliquet. Nam efficitur libero id enim fringilla, a rhoncus est commodo. Etiam massa est, pulvinar ac imperdiet fermentum, dapibus quis metus. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Donec eu ornare massa, nec scelerisque ipsum. Phasellus id lacinia turpis. Nulla feugiat ex vitae lacus pellentesque, eu rhoncus dui pharetra.';
		}
		
		
		/**
		 * Attempts to generate the number of articles defined by howManyArticles
		 *
		 * @throws \Exception
		 * @since version
		 */
		private function generateArticles(): void
		{
			$db = Factory::getDbo();
			
			// one call to $this->getLoremIpsum() rather than $maxCounter calls
			$fakeText = $this->getLoremIpsum();
			
			// one call to strlen rather than $maxCounter calls
			
			$fakeTextLength = strlen($fakeText);
			for ($counter = 0, $maxCounter = $this->getHowManyArticles(); $counter < $maxCounter; $counter++)
			{
				$randomString = bin2hex(random_bytes(4));
				$textStart    = random_int(0, ($fakeTextLength - 1));
				
				// only create what is required for an article
				$article               = new stdClass();
				$article->title        = 'Article ' . $randomString;
				$article->alias        = 'article-' . $randomString;
				$article->catid        = 9; // uncategorized
				$article->introtext    = substr($fakeText, $textStart, 60);
				$article->fulltext     = substr($fakeText, $textStart);
				$article->language     = '*';
				$article->access       = 1;
				$article->publish_up   = '0000-00-00 00:00:00';
				$article->publish_down = '0000-00-00 00:00:00';
				$article->state        = 1; //published automatically
				
				$result = $db->insertObject('#__content', $article);
				
				if ($result !== true)
				{
					throw new RuntimeException('Could not create article');
				}
				
				$this->out($article->title . ' was created.');
				
			}
		}
	}
}
CliApplication::getInstance('ContentCli')->execute();
