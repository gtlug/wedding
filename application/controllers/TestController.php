<?php
require_once("Abstract.php");
class TestController extends Controller_Abstract
{
	public function init()
	{
		die();
	}
	
	public function indexAction()
	{
		// send them somewhere more useful
		$this->_redirect('/home');
	}
	
	public function processAddressDataAction()
	{
		header("Content-Type: text/plain");
		$rowDelim = "\n";
		$colDelim = "\t";
		$sourceFilename = Zend_Registry::get('site_root') . 'tmp/addressData.csv';
		$outFilename = Zend_Registry::get('site_root') . 'tmp/addressData-out.csv';
		$source = file_get_contents($sourceFilename);
		
		$out = "";
		
		$rows = explode($rowDelim, $source);
		$addressRegex = "/^(?P<street>[^,]+),\s*\b(?P<city>[^,]+)\b[\s,]+(?P<state>\w+)\s+(?P<zip>[\d\-]+)$/i";
		foreach($rows as $row)
		{
			$cols = explode($colDelim, $row);
			if(count($cols) < 3) continue;
			
			$name = $cols[0];
			$invited = $cols[1];
			$found = preg_match($addressRegex, trim($cols[2]), $parts);
			$newCols = array();
			if($found)
			{
				$newCols = array(
					trim($name),
					trim($parts['street']),
					trim($parts['city']),
					strtoupper(substr(trim($parts['state']), 0, 2)),
					trim($parts['zip'])
				);
			}
			else
			{
				print "NOT FOUND: ($name) {$cols[2]}\n";
				$newCols[] = $name;
			}
			
			$out .= implode($colDelim, $newCols) . $rowDelim;
		}
		print $out;
		file_put_contents($outFilename, $out);
		
		die("\n\nFINISHED!");
	}
	
	public function importInvitesAction()
	{
		header("Content-Type: text/plain");
		
		// set up db objects
		$db = Zend_Registry::get('db');
		$invitesTable = new Wedding_Db_Table_Invites($db);
		$guestsTable = new Wedding_Db_Table_Guests($db);
		$now = new Zend_Db_Expr('NOW()');
		
		// set up data source
		$rowDelim = "\n";
		$colDelim = "\t";
		$sourceFilename = Zend_Registry::get('site_root') . 'tmp/invites-guests.csv';
		//$outFilename = Zend_Registry::get('site_root') . 'tmp/addressData-out.csv';
		$in = file_get_contents($sourceFilename);
		$out = "";
		
		$rows = explode($rowDelim, $in);
		// first row is headers
		array_shift($rows);
		foreach($rows as $row)
		{
			$cols = explode($colDelim, $row);
			// validate row
			if((count($cols) < 3) || (false === array_search(true, $cols)))
			{
				print "Not a valid row";
				continue;
			}
			//print_r($cols);
			$inviteData = array(
				'mailingName' => trim(array_shift($cols)),
				'names' => strtolower(trim(array_shift($cols))),
				'guests' => (int)trim(array_shift($cols)),
				'dateAdded' => $now
			);
			
			$inviteRow = $invitesTable->createRow($inviteData);
			$inviteRow->save();
			
			print "\n\n------INVITE-------\n";
			print_r($inviteRow->toArray());
			
			//the rest of the row is guest names
			foreach($cols as $guestName)
			{
				$guestName = trim($guestName);
				//not all cols are valuable, so stop adding guests when nothing there
				if(!$guestName)
				{
					break;
				}
				$guestData = array(
					'inviteId' => $inviteRow->inviteId,
					'guestName' => $guestName,
					'dateUpdated' => $now,
					'dateAdded' => $now
				);
				$guestRow = $guestsTable->createRow($guestData);
				$guestRow->save();
				
				print "--GUEST--\n";
				print_r($guestRow->toArray());
			}
			//$out .= implode($colDelim, $newCols) . $rowDelim;
		}
		print $out;
		//file_put_contents($outFilename, $out);
		
		die("\n\nFINISHED!");
	}
}
?>