<?php

namespace Service;


class AuthenticationService
{
    public function authenticateUser($username, $password)
    {
        $usertable = $this->hashTable();
        return isset($usertable[$username]) && $usertable[$username] == $password;
    }

    public function authorizeUser()
    {

    }

    private function hashTable()
    {
        $this->currentCommonPassword();
        $userlogins = [
            'test' => 'test',
            'solbybo' => $this->currentCommonPassword(),
            'redaktör' => 'rötkader2018!'
        ];
        return $userlogins;
    }

    public function getHouseOnCall()
    {
        return $this->currentCommonPassword();
    }

    private function currentCommonPassword()
    {
        $startingMonth = date_create('2017-12-01');
        $currentMonthString = sprintf("%s-%s-01", date('Y'), date('m'));
        $currentMonth = date_create($currentMonthString);
        $dateDiff = date_diff($startingMonth, $currentMonth);
        $monthDiff = $dateDiff->m;
        $whosMonth = $monthDiff % 9;
        switch ($whosMonth) {
            case 1:
                return 'Violen';
                break;
            case 2:
                return 'Blåsippan';
                break;
            case 3:
                return 'Konvaljen';
                break;
            case 4:
                return 'Gullvivan';
                break;
            case 5:
                return 'Hästhoven';
                break;
            case 6:
                return 'Maskrosen';
                break;
            case 7:
                return 'Prästkragen';
                break;
            case 8:
                return 'Tusenskönan';
                break;
            case 0:
                return 'Blåklockan';
                break;
        }
    }

    public function UserGroup($user)
    {
        $userGroups = [
            'test' => ['Common', 'Editor', 'Superuser'],
            'solbybo' => ['Common'],
            'redaktör' => ['Common', 'Editor'],
            'su' => ['Common', 'Editor', 'Superuser']
        ];
        if (!isset($userGroups[$user])) {
            return 'Common';
        }
        return $userGroups[$user];
    }
}
