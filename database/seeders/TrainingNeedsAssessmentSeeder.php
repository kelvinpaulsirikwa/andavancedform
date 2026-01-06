<?php

namespace Database\Seeders;

use App\Models\TrainingNeedsAssessment;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class TrainingNeedsAssessmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $firstNames = ['John', 'Mary', 'James', 'Patricia', 'Robert', 'Jennifer', 'Michael', 'Linda', 'William', 'Elizabeth', 'David', 'Barbara', 'Richard', 'Susan', 'Joseph', 'Jessica', 'Thomas', 'Sarah', 'Charles', 'Karen', 'Christopher', 'Nancy', 'Daniel', 'Lisa', 'Matthew', 'Betty', 'Anthony', 'Margaret', 'Mark', 'Sandra'];
        $middleNames = ['A.', 'B.', 'C.', 'D.', 'E.', 'F.', 'G.', 'H.', 'I.', 'J.', 'K.', 'L.', 'M.', 'N.', 'O.', 'P.', 'Q.', 'R.', 'S.', 'T.', 'U.', 'V.', 'W.', 'X.', 'Y.', 'Z.', null, null, null, null];
        $surnames = ['Smith', 'Johnson', 'Williams', 'Brown', 'Jones', 'Garcia', 'Miller', 'Davis', 'Rodriguez', 'Martinez', 'Hernandez', 'Lopez', 'Wilson', 'Anderson', 'Thomas', 'Taylor', 'Moore', 'Jackson', 'Martin', 'Lee', 'Thompson', 'White', 'Harris', 'Sanchez', 'Clark', 'Ramirez', 'Lewis', 'Robinson', 'Walker', 'Young'];
        $jobTitles = ['Finance Officer', 'Accountant', 'Administrative Assistant', 'HR Manager', 'IT Specialist', 'Program Coordinator', 'Research Analyst', 'Data Entry Clerk', 'Operations Manager', 'Project Manager', 'Training Officer', 'Compliance Officer', 'Auditor', 'Budget Analyst', 'Procurement Officer', 'Legal Advisor', 'Communications Officer', 'Policy Analyst', 'Executive Assistant', 'Database Administrator'];
        $workStations = ['Head Office', 'Dar es Salaam Branch', 'Arusha Branch', 'Mwanza Branch', 'Dodoma Branch', 'Mbeya Branch', 'Tanga Branch', 'Morogoro Branch', 'Zanzibar Branch', 'Mtwara Branch'];
        $supervisors = ['Dr. John Mwangi', 'Ms. Sarah Kimani', 'Mr. Peter Ochieng', 'Dr. Grace Wanjiku', 'Mr. David Otieno', 'Ms. Jane Wambui', 'Dr. Michael Kipchoge', 'Ms. Anne Njeri', 'Mr. James Kamau', 'Dr. Lucy Muthoni'];
        
        $qualificationOptions = [
            ['type' => 'Certificate', 'award' => 'Certificate in Accounting', 'institution' => 'University of Dar es Salaam'],
            ['type' => 'Diploma', 'award' => 'Diploma in Business Administration', 'institution' => 'Institute of Finance Management'],
            ['type' => 'Bachelor Degree', 'award' => 'Bachelor of Commerce', 'institution' => 'University of Dar es Salaam'],
            ['type' => 'Master Degree', 'award' => 'Master of Business Administration', 'institution' => 'Mzumbe University'],
            ['type' => 'PhD', 'award' => 'Doctor of Philosophy in Finance', 'institution' => 'University of Dar es Salaam'],
        ];
        
        $pastTrainingTopics = [
            ['name' => 'Financial Management', 'date_attended' => '2023-06-15'],
            ['name' => 'Project Management', 'date_attended' => '2023-08-20'],
            ['name' => 'Leadership Skills', 'date_attended' => '2023-10-10'],
            ['name' => 'Data Analysis', 'date_attended' => '2024-01-25'],
            ['name' => 'Communication Skills', 'date_attended' => '2024-03-12'],
            ['name' => 'Strategic Planning', 'date_attended' => '2024-05-08'],
            ['name' => 'Risk Management', 'date_attended' => '2024-07-18'],
            ['name' => 'Customer Service', 'date_attended' => '2024-09-05'],
        ];
        
        $competencyOptions = [
            'financial_management', 'budgeting', 'accounting', 'auditing', 'procurement',
            'human_resource_management', 'project_management', 'strategic_planning',
            'report_writing', 'data_analysis', 'communication', 'leadership',
            'team_management', 'problem_solving', 'decision_making', 'time_management',
            'customer_service', 'negotiation', 'presentation_skills', 'it_skills'
        ];
        
        $desiredTrainingTopics = [
            'Advanced Financial Analysis',
            'Digital Transformation',
            'Change Management',
            'Performance Management',
            'Conflict Resolution',
            'Public Speaking',
            'Advanced Excel',
            'Data Visualization',
            'Strategic Leadership',
            'Risk Assessment',
            'Compliance and Regulations',
            'Team Building',
        ];
        
        $trainingMethods = [
            'classroom' => ['Not very effective', 'Somewhat effective', 'Very effective'],
            'online' => ['Not very effective', 'Somewhat effective', 'Very effective'],
            'workshop' => ['Not very effective', 'Somewhat effective', 'Very effective'],
            'seminar' => ['Not very effective', 'Somewhat effective', 'Very effective'],
            'on_the_job' => ['Not very effective', 'Somewhat effective', 'Very effective'],
        ];

        for ($i = 0; $i < 30; $i++) {
            $gender = ['Male', 'Female'][rand(0, 1)];
            $age = rand(25, 55);
            $firstName = $firstNames[$i];
            $middleName = $middleNames[rand(0, count($middleNames) - 1)];
            $surname = $surnames[$i];
            $jobTitle = $jobTitles[rand(0, count($jobTitles) - 1)];
            $workStation = $workStations[rand(0, count($workStations) - 1)];
            $supervisor = $supervisors[rand(0, count($supervisors) - 1)];
            
            // Random qualifications (1-3)
            $numQualifications = rand(1, 3);
            $qualifications = [];
            for ($j = 0; $j < $numQualifications; $j++) {
                $qualifications[] = $qualificationOptions[rand(0, count($qualificationOptions) - 1)];
            }
            
            // Random past trainings (0-4)
            $numPastTrainings = rand(0, 4);
            $pastTrainings = [];
            $usedTrainings = [];
            for ($j = 0; $j < $numPastTrainings; $j++) {
                $training = $pastTrainingTopics[rand(0, count($pastTrainingTopics) - 1)];
                if (!in_array($training['name'], $usedTrainings)) {
                    $pastTrainings[] = $training;
                    $usedTrainings[] = $training['name'];
                }
            }
            
            // Random competencies (5-12)
            $numCompetencies = rand(5, 12);
            $selectedCompetencies = [];
            $usedCompetencies = [];
            while (count($selectedCompetencies) < $numCompetencies) {
                $comp = $competencyOptions[rand(0, count($competencyOptions) - 1)];
                if (!in_array($comp, $usedCompetencies)) {
                    $selectedCompetencies[] = $comp;
                    $usedCompetencies[] = $comp;
                }
            }
            
            // Random desired trainings (1-3)
            $numDesiredTrainings = rand(1, 3);
            $desiredTrainings = [];
            $usedDesired = [];
            for ($j = 0; $j < $numDesiredTrainings; $j++) {
                $training = $desiredTrainingTopics[rand(0, count($desiredTrainingTopics) - 1)];
                if (!in_array($training, $usedDesired)) {
                    $desiredTrainings[] = $training;
                    $usedDesired[] = $training;
                }
            }
            
            // Random training method ratings
            $trainingMethodsData = [];
            foreach ($trainingMethods as $method => $ratings) {
                $trainingMethodsData[$method] = $ratings[rand(0, 2)];
            }
            
            // Random dates
            $signatureDate = Carbon::now()->subDays(rand(1, 90));
            $supervisorDate = $signatureDate->copy()->addDays(rand(1, 7));
            
            TrainingNeedsAssessment::create([
                'gender' => $gender,
                'age' => $age,
                'first_name' => $firstName,
                'middle_name' => $middleName,
                'surname' => $surname,
                'job_title' => $jobTitle,
                'work_station' => $workStation,
                'immediate_supervisor_name' => $supervisor,
                'qualifications' => $qualifications,
                'past_trainings' => $pastTrainings,
                'competencies' => $selectedCompetencies,
                'desired_trainings' => $desiredTrainings,
                'training_methods' => $trainingMethodsData,
                'other_comments' => rand(0, 1) ? 'Looking forward to enhancing my skills through these training opportunities.' : null,
                'signature_name' => $firstName . ' ' . ($middleName ? $middleName . ' ' : '') . $surname,
                'signature_date' => $signatureDate,
                'supervisor_performance_comment' => rand(0, 1) ? 'The staff member has shown good performance and would benefit from the suggested training programs.' : 'Performance is satisfactory. Training recommendations are appropriate.',
                'supervisor_training_suggestions' => $desiredTrainings,
                'supervisor_name' => $supervisor,
                'supervisor_signature' => $supervisor,
                'supervisor_date' => $supervisorDate,
            ]);
        }
    }
}

