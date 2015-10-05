<?php
namespace User\Auth;

use ADmad\JwtAuth\Auth\JwtAuthenticate;
use Cake\ORM\TableRegistry;

/**
 * An authentication adapter for authenticating using JSON Web Tokens.
 *
 * ```
 *  $this->Auth->config('authenticate', [
 *      'ADmad/JwtAuth.Jwt' => [
 *          'parameter' => '_token',
 *          'userModel' => 'Users',
 *          'scope' => ['User.active' => 1]
 *          'fields' => [
 *              'id' => 'id'
 *          ],
 *      ]
 *  ]);
 * ```
 *
 * @copyright 2014 A. Sarela aka ADmad
 * @license MIT
 * @see http://jwt.io
 * @see http://tools.ietf.org/html/draft-ietf-oauth-json-web-token
 */
class AcadiosJwtAuthenticate extends JwtAuthenticate
{
   
    /**
     * Find a user record.
     *
     * @param string $token The token identifier.
     * @param string $password Unused password.
     * @return bool|array Either false on failure, or an array of user data.
     */
    protected function _findUser($token, $password = null)
    {
        $user = parent::_findUser($token, $password);
        $profile = $this->getProfileInfo($user['usertype_id'], $user['id']);

        if ($profile) {
            $user['profile'] = $profile;
            if ($user['usertype_id'] == 2) {
                $user['institution']['id'] = $user['profile']['id'];
            }
            if ($user['usertype_id'] == 1 || $user['usertype_id'] == 3) {
                $user['institution'] = $this->getInstitutionId($user['usertype_id'], $user['profile']['id']);
            }
        }
        
        return $user;
    }

    /**
     * getProfile info from a authenticated user
     * @param int $usertypeId id of usertype.
     * @param int $userID id of authenticated user.
     */
    public function getProfileInfo($usertypeId, $userId)
    {
        if ($usertypeId == 100) {
            return false;
        }
        switch ($usertypeId) {
            case 1://Student
                $model = 'Students';
                break;
            case 2://Institution
                $model = 'Institutions';
                break;
            case 3://Teacher
                $model = 'Teachers';
                break;
            case 4://Tutor
                $model = 'Tutors';
                break;
            default:
                # code...
                break;
        }

        $table = TableRegistry::get($model);
        $profile = $table->findByUser_id($userId)->select(['id'])->first();
        if (!is_null($profile)) {
            return $profile->toArray();
        }
        return false;
    }

    /**
     * getInstitutionId get id of related institution
     * @param int $usertypeId id of usertype.
     * @param int $userID id of authenticated user.
     * @return id of institution
     */
    public function getInstitutionId($usertypeId, $profileId)
    {
        if ($usertypeId == 100 || $usertypeId == 2 || $usertypeId == 4) {
            return false;
        }
        switch ($usertypeId) {
            case 1://Student
                $model = 'InstitutionsStudents';
                $modelField = 'student_id';
                break;
            case 3://Teacher
                $model = 'InstitutionsTeachers';
                $modelField = 'teacher_id';
                break;
            default:
                # code...
                break;
        }

        $table = TableRegistry::get($model);
        $profile = $table->find()
            ->where([$model. '.' .$modelField => $profileId])
            ->select(['id'])
            ->first();
        if (!is_null($profile)) {
            return $profile->toArray();
        }
        return false;
    }
}
