<?php

namespace App\Http\Controllers\Bulk;

use App\Http\Controllers\Controller;
use App\Models\OrganizationalStructure\Branch;
use App\Models\OrganizationalStructure\Company;
use App\Models\OrganizationalStructure\Department;
use App\Models\OrganizationalStructure\JobTitle;
use App\Models\OrganizationalStructure\Section;
use App\Models\OrganizationalStructure\WorkforceDivision;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\DB;
use App\Traits\ResponseTrait;
use App\Traits\MailTrait;
use App\Http\Controllers\Auth\SignedUrlController;
use App\Models\Credential;
use App\Models\ContactNumber;
use App\Models\EmergencyContact;
use App\Models\EmploymentInformation;
use App\Models\GovernmentInformation;
use App\Models\PersonalInformation;

class BulkController extends Controller
{
    use ResponseTrait;
    use MailTrait;
    public Credential $credential;
    public PersonalInformation $personalInformation;
    public ContactNumber $contactNumber;
    public EmergencyContact $emergencyContact;
    public GovernmentInformation $governmentInformation;
    public EmploymentInformation $employedInformation;
    public function onBulkUploadEmployee(Request $request)
    {
        try {
            DB::beginTransaction();

            $file = $request['bulk_data'];
            $currentTime = now();
            foreach (json_decode($file) as $row) {
                $credentialExist = Credential::where('employee_id', $row->employee_id)->exists();
                if ($credentialExist) {
                    continue;
                }
                $this->credential = new Credential();
                $this->credential->employee_id = $row->employee_id;
                $this->credential->status = 1;
                $this->credential->created_at = $currentTime;
                $this->credential->updated_at = $currentTime;
                $this->credential->save();

                $this->personalInformation = new PersonalInformation();
                $this->personalInformation->employee_id = $row->employee_id;
                $this->personalInformation->first_name = $row->first_name;
                $this->personalInformation->middle_name = $row->middle_name;
                $this->personalInformation->last_name = $row->last_name;
                $this->personalInformation->prefix = $row->prefix;
                $this->personalInformation->suffix = $row->suffix;
                $this->personalInformation->gender = $row->gender;
                $this->personalInformation->birth_date = $row->birth_date ? \DateTime::createFromFormat('n/j/Y', $row->birth_date)->format('Y-n-j') : null;
                $this->personalInformation->age = $row->age != '' || $row->age != null ? $row->age : null;
                $this->personalInformation->marital_status = $row->marital_status;
                $this->personalInformation->personal_email = $row->personal_email;
                $this->personalInformation->company_email = $row->company_email;
                $this->personalInformation->created_at = $currentTime;
                $this->personalInformation->updated_at = $currentTime;
                $this->personalInformation->save();

                $this->contactNumber = new ContactNumber();
                $this->contactNumber->personal_information_id = $this->personalInformation->id;
                $this->contactNumber->phone_number = $row->personal_contact_number;
                $this->contactNumber->type = 0;
                $this->contactNumber->status = 1;
                $this->contactNumber->created_at = $currentTime;
                $this->contactNumber->updated_at = $currentTime;
                $this->contactNumber->save();

                $this->emergencyContact = new EmergencyContact();
                $this->emergencyContact->personal_information_id = $this->personalInformation->id;
                $this->emergencyContact->name = $row->emergency_contact_full_name;
                $this->emergencyContact->phone_number = $row->emergency_contact_phone_number;
                $this->emergencyContact->relationship = $row->emergency_contact_relationship;
                $this->emergencyContact->status = 1;
                $this->emergencyContact->created_at = $currentTime;
                $this->emergencyContact->updated_at = $currentTime;
                $this->emergencyContact->save();

                $this->governmentInformation = new GovernmentInformation();
                $this->governmentInformation->personal_information_id = $this->personalInformation->id;
                $this->governmentInformation->sss_number = $row->sss_number;
                $this->governmentInformation->philhealth_number = $row->philhealth_number;
                $this->governmentInformation->pagibig_number = $row->pagibig_number;
                $this->governmentInformation->tin_number = $row->tin_number;
                $this->governmentInformation->created_at = $currentTime;
                $this->governmentInformation->updated_at = $currentTime;
                $this->governmentInformation->save();

                // $this->employedInformation = new EmploymentInformation();
                // $this->employedInformation->personal_information_id = $this->personalInformation->id;
                // $this->employedInformation->company_id = $this->onCheckCompanyCode($row->company_code);
                // $this->employedInformation->branch_id = $this->onCheckBranchCode($row->branch_code);
                // $this->employedInformation->department_id = $this->onCheckDepartmentCode($row->department_code);
                // $this->employedInformation->section_id = $this->onCheckSectionCode($row->section_code);
                // $this->employedInformation->sub_section_id = $this->onCheckSectionCode($row->sub_section_code);
                // $this->employedInformation->position_id = $row->job_code == ' ' ? null : $this->onCheckPositionCode($row->job_code)->id;
                // $this->employedInformation->job_title_id = $row->job_code == ' ' ? null : $this->onCheckPositionCode($row->job_code)->id;
                // $this->employedInformation->workforce_division_id = $this->onCheckWorkDivisionCode($row->workforce_division_code)->id;
                // $this->employedInformation->employment_classification = $row->employment_classification;
                // $this->employedInformation->date_hired = \DateTime::createFromFormat('n/j/Y', $row->datea_hired)->format('Y-n-j');
                // $this->employedInformation->onboarding_status = $row->onboarding_status;
                // $this->employedInformation->created_at = $currentTime;
                // $this->employedInformation->updated_at = $currentTime;
                // $this->employedInformation->save();
                // api
                // $this->onEmailBlast($row);
                DB::commit();

                /* $body = [
                    "apiKey" => "SWPG6BJaxZ0IjfRV1K1SAQvOiVbQuY",
                    "apiSecret" => "_-0Ww33ewrXppW2I_U8LzH7aUma_JOmr",
                    "from" => "PTXTrial",
                    "to" => $row->personal_contact_number,
                    "text" => "Hello! You've been successfully registered with One Mary Grace.Your account has been created, and your login credentials are awaiting you in either your personal or company email inbox."
                ];
                Http::post('https://api.promotexter.com/sms/send', $body); */
            }
            /*  foreach ($dataToInsert as $row) {
                $credentialData[] = [
                    'employee_id' => $row['employee_id'],
                    'password' => $row['password'],
                    'status' => $row['status'],
                    'created_at' => $currentTime,
                    'updated_at' => $currentTime,
                ];
                $personalInformationData[] = [
                    'employee_id' => $row['employee_id'],
                    'first_name' => $row['first_name'],
                    'middle_name' => $row['middle_name'],
                    'last_name' => $row['last_name'],
                    'status' => $row['status'],
                    'created_at' => $currentTime,
                    'updated_at' => $currentTime,
                ];
            }
            Credential::insert($credentialData);
            PersonalInformation::insert($personalInformationData); */
            return $this->dataResponse('success', 200, __('msg.bulk_upload_success'));
        } catch (Exception $exception) {
            DB::rollBack();
            return $this->dataResponse('error', 400, $exception->getMessage());
        }
    }
    public function onBulkUpdateEmployeeInformation(Request $request)
    {
        DB::beginTransaction();
        try {
            $file = $request['bulk_data'];
            foreach (json_decode($file) as $row) {
                DB::beginTransaction();
                $personalInformation = PersonalInformation::where('employee_id', $row->employee_id)->first();
                $employeeInformation = EmploymentInformation::where('personal_information_id', $personalInformation->id)->first();

                if ($employeeInformation) {
                    $employeeInformation->department_id = $this->onCheckDepartmentCode($row->department_code);
                    $employeeInformation->save();
                } else {
                    $employmentInformationModel = new EmploymentInformation();
                    $employmentInformationModel->personal_information_id = $personalInformation->id;
                    $employmentInformationModel->department_id = $this->onCheckDepartmentCode($row->department_code);
                    $employmentInformationModel->save();
                }
            }
            DB::commit();
        } catch (Exception $exception) {
            DB::rollBack();
            return $this->dataResponse('error', 400, $exception->getMessage());
        }
    }
    public function onEmailBlast($row)
    {
        $credentialQuery = Credential::where('employee_id', $row->employee_id);
        $signedController = new SignedUrlController();
        $temporaryUrl = $signedController->onCreateSignedUrl($credentialQuery, 'create', 'password/create');
        $full_name = $row->first_name . ' ' . $row->last_name;
        $this->onSendSignedUrl($row->personal_email, 'create', $full_name, $temporaryUrl);
    }
    public function onRequestEmailBlast(Request $request)
    {
        $fields = $request->validate([
            'bulk_data' => 'required'
        ]);
        try {
            $bulkData = json_decode($fields['bulk_data']);
            foreach ($bulkData as $value) {
                $this->onEmailBlast($value);
            }
            return $this->dataResponse('success', 200, __('msg.bulk_upload_success'));
        } catch (Exception $exception) {
            return $this->dataResponse('error', 400, $exception->getMessage());
        }
    }
    public function onCheckDepartmentCode($department_code)
    {
        return Department::where('department_code', $department_code)->first()->id ?? null;
    }
    public function onCheckSectionCode($section_code)
    {
        return Section::where('section_code', $section_code)->first()->id ?? null;
    }
    public function onCheckCompanyCode($company_code)
    {
        return Company::where('company_code', $company_code)->first()->id ?? null;
    }
    public function onCheckBranchCode($branch_code)
    {
        return Branch::where('branch_code', $branch_code)->first()->id ?? null;
    }
    public function onCheckPositionCode($job_code)
    {
        return JobTitle::where('job_code', $job_code)->first()->id ?? null;
    }
    public function onCheckWorkDivisionCode($workforce_division_code)
    {
        return WorkforceDivision::where('workforce_division_code', $workforce_division_code)->first()->id ?? null;
    }
}
