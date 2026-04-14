<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ModulesSeeder extends Seeder
{
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();
        DB::table('modules')->truncate();
        Schema::enableForeignKeyConstraints();

        $modules = [
            // ==================== ICT DEPARTMENT (dept 1) ====================
            // Program 1 - Multimedia Technology
            ['module_name' => 'Introduction to Multimedia',   'module_code' => 'MM401', 'module_credit' => 10, 'semester' => 'Semester 1', 'nta_level' => '4', 'program_id' => 1],
            ['module_name' => 'Graphic Design Basics',        'module_code' => 'MM402', 'module_credit' => 10, 'semester' => 'Semester 1', 'nta_level' => '4', 'program_id' => 1],
            ['module_name' => 'Photography and Imaging',      'module_code' => 'MM403', 'module_credit' => 10, 'semester' => 'Semester 1', 'nta_level' => '4', 'program_id' => 1],
            ['module_name' => 'Audio Production',             'module_code' => 'MM404', 'module_credit' => 10, 'semester' => 'Semester 2', 'nta_level' => '4', 'program_id' => 1],
            ['module_name' => 'Video Editing',                'module_code' => 'MM405', 'module_credit' => 10, 'semester' => 'Semester 2', 'nta_level' => '4', 'program_id' => 1],
            ['module_name' => 'Animation Techniques',         'module_code' => 'MM501', 'module_credit' => 10, 'semester' => 'Semester 1', 'nta_level' => '5', 'program_id' => 1],
            ['module_name' => 'Web Design',                   'module_code' => 'MM502', 'module_credit' => 10, 'semester' => 'Semester 1', 'nta_level' => '5', 'program_id' => 1],
            ['module_name' => 'UI/UX Design',                 'module_code' => 'MM503', 'module_credit' => 10, 'semester' => 'Semester 1', 'nta_level' => '5', 'program_id' => 1],
            ['module_name' => 'Motion Graphics',              'module_code' => 'MM504', 'module_credit' => 10, 'semester' => 'Semester 2', 'nta_level' => '5', 'program_id' => 1],
            ['module_name' => '3D Modeling',                  'module_code' => 'MM505', 'module_credit' => 10, 'semester' => 'Semester 2', 'nta_level' => '5', 'program_id' => 1],
            ['module_name' => 'Advanced Animation',           'module_code' => 'MM601', 'module_credit' => 10, 'semester' => 'Semester 1', 'nta_level' => '6', 'program_id' => 1],
            ['module_name' => 'Game Design',                  'module_code' => 'MM602', 'module_credit' => 10, 'semester' => 'Semester 1', 'nta_level' => '6', 'program_id' => 1],
            ['module_name' => 'Digital Marketing Media',      'module_code' => 'MM603', 'module_credit' => 10, 'semester' => 'Semester 2', 'nta_level' => '6', 'program_id' => 1],
            ['module_name' => 'Film Production',              'module_code' => 'MM604', 'module_credit' => 10, 'semester' => 'Semester 2', 'nta_level' => '6', 'program_id' => 1],
            ['module_name' => 'Multimedia Project',           'module_code' => 'MM605', 'module_credit' => 10, 'semester' => 'Semester 2', 'nta_level' => '6', 'program_id' => 1],

            // Program 2 - Cyber Security
            ['module_name' => 'Introduction to Cyber Security','module_code' => 'CS401', 'module_credit' => 10, 'semester' => 'Semester 1', 'nta_level' => '4', 'program_id' => 2],
            ['module_name' => 'Network Fundamentals',          'module_code' => 'CS402', 'module_credit' => 10, 'semester' => 'Semester 1', 'nta_level' => '4', 'program_id' => 2],
            ['module_name' => 'Operating Systems Basics',      'module_code' => 'CS403', 'module_credit' => 10, 'semester' => 'Semester 1', 'nta_level' => '4', 'program_id' => 2],
            ['module_name' => 'Computer Hardware',             'module_code' => 'CS404', 'module_credit' => 10, 'semester' => 'Semester 2', 'nta_level' => '4', 'program_id' => 2],
            ['module_name' => 'Programming Fundamentals',      'module_code' => 'CS405', 'module_credit' => 10, 'semester' => 'Semester 2', 'nta_level' => '4', 'program_id' => 2],
            ['module_name' => 'Ethical Hacking',               'module_code' => 'CS501', 'module_credit' => 10, 'semester' => 'Semester 1', 'nta_level' => '5', 'program_id' => 2],
            ['module_name' => 'Network Security',              'module_code' => 'CS502', 'module_credit' => 10, 'semester' => 'Semester 1', 'nta_level' => '5', 'program_id' => 2],
            ['module_name' => 'Database Security',             'module_code' => 'CS503', 'module_credit' => 10, 'semester' => 'Semester 1', 'nta_level' => '5', 'program_id' => 2],
            ['module_name' => 'Cryptography',                  'module_code' => 'CS504', 'module_credit' => 10, 'semester' => 'Semester 2', 'nta_level' => '5', 'program_id' => 2],
            ['module_name' => 'Web Security',                  'module_code' => 'CS505', 'module_credit' => 10, 'semester' => 'Semester 2', 'nta_level' => '5', 'program_id' => 2],
            ['module_name' => 'Advanced Cyber Security',       'module_code' => 'CS601', 'module_credit' => 10, 'semester' => 'Semester 1', 'nta_level' => '6', 'program_id' => 2],
            ['module_name' => 'Digital Forensics',             'module_code' => 'CS602', 'module_credit' => 10, 'semester' => 'Semester 1', 'nta_level' => '6', 'program_id' => 2],
            ['module_name' => 'Penetration Testing',           'module_code' => 'CS603', 'module_credit' => 10, 'semester' => 'Semester 2', 'nta_level' => '6', 'program_id' => 2],
            ['module_name' => 'Cloud Security',                'module_code' => 'CS604', 'module_credit' => 10, 'semester' => 'Semester 2', 'nta_level' => '6', 'program_id' => 2],
            ['module_name' => 'Cyber Risk Management',         'module_code' => 'CS605', 'module_credit' => 10, 'semester' => 'Semester 2', 'nta_level' => '6', 'program_id' => 2],

            // Program 3 - Information Technology
            ['module_name' => 'Introduction to IT',            'module_code' => 'IT401', 'module_credit' => 10, 'semester' => 'Semester 1', 'nta_level' => '4', 'program_id' => 3],
            ['module_name' => 'Computer Applications',         'module_code' => 'IT402', 'module_credit' => 10, 'semester' => 'Semester 1', 'nta_level' => '4', 'program_id' => 3],
            ['module_name' => 'Database Basics',               'module_code' => 'IT403', 'module_credit' => 10, 'semester' => 'Semester 1', 'nta_level' => '4', 'program_id' => 3],
            ['module_name' => 'Networking Basics',             'module_code' => 'IT404', 'module_credit' => 10, 'semester' => 'Semester 2', 'nta_level' => '4', 'program_id' => 3],
            ['module_name' => 'Programming Basics',            'module_code' => 'IT405', 'module_credit' => 10, 'semester' => 'Semester 2', 'nta_level' => '4', 'program_id' => 3],
            ['module_name' => 'Web Development',               'module_code' => 'IT501', 'module_credit' => 10, 'semester' => 'Semester 1', 'nta_level' => '5', 'program_id' => 3],
            ['module_name' => 'System Analysis and Design',    'module_code' => 'IT502', 'module_credit' => 10, 'semester' => 'Semester 1', 'nta_level' => '5', 'program_id' => 3],
            ['module_name' => 'Database Management',           'module_code' => 'IT503', 'module_credit' => 10, 'semester' => 'Semester 1', 'nta_level' => '5', 'program_id' => 3],
            ['module_name' => 'Computer Networks',             'module_code' => 'IT504', 'module_credit' => 10, 'semester' => 'Semester 2', 'nta_level' => '5', 'program_id' => 3],
            ['module_name' => 'Software Engineering',          'module_code' => 'IT505', 'module_credit' => 10, 'semester' => 'Semester 2', 'nta_level' => '5', 'program_id' => 3],
            ['module_name' => 'Advanced Programming',          'module_code' => 'IT601', 'module_credit' => 10, 'semester' => 'Semester 1', 'nta_level' => '6', 'program_id' => 3],
            ['module_name' => 'Cloud Computing',               'module_code' => 'IT602', 'module_credit' => 10, 'semester' => 'Semester 1', 'nta_level' => '6', 'program_id' => 3],
            ['module_name' => 'Cyber Security Basics',         'module_code' => 'IT603', 'module_credit' => 10, 'semester' => 'Semester 2', 'nta_level' => '6', 'program_id' => 3],
            ['module_name' => 'IT Project Management',         'module_code' => 'IT604', 'module_credit' => 10, 'semester' => 'Semester 2', 'nta_level' => '6', 'program_id' => 3],
            ['module_name' => 'Final Year Project',            'module_code' => 'IT605', 'module_credit' => 10, 'semester' => 'Semester 2', 'nta_level' => '6', 'program_id' => 3],

            // Program 4 - Computer Science
            ['module_name' => 'Discrete Mathematics',          'module_code' => 'CP401', 'module_credit' => 10, 'semester' => 'Semester 1', 'nta_level' => '4', 'program_id' => 4],
            ['module_name' => 'Introduction to Programming',   'module_code' => 'CP402', 'module_credit' => 10, 'semester' => 'Semester 1', 'nta_level' => '4', 'program_id' => 4],
            ['module_name' => 'Computer Organization',         'module_code' => 'CP403', 'module_credit' => 10, 'semester' => 'Semester 2', 'nta_level' => '4', 'program_id' => 4],
            ['module_name' => 'Data Structures',               'module_code' => 'CP501', 'module_credit' => 10, 'semester' => 'Semester 1', 'nta_level' => '5', 'program_id' => 4],
            ['module_name' => 'Algorithms',                    'module_code' => 'CP502', 'module_credit' => 10, 'semester' => 'Semester 1', 'nta_level' => '5', 'program_id' => 4],
            ['module_name' => 'Operating Systems',             'module_code' => 'CP503', 'module_credit' => 10, 'semester' => 'Semester 2', 'nta_level' => '5', 'program_id' => 4],
            ['module_name' => 'Artificial Intelligence',       'module_code' => 'CP601', 'module_credit' => 10, 'semester' => 'Semester 1', 'nta_level' => '6', 'program_id' => 4],
            ['module_name' => 'Machine Learning',              'module_code' => 'CP602', 'module_credit' => 10, 'semester' => 'Semester 2', 'nta_level' => '6', 'program_id' => 4],
            ['module_name' => 'Research Project',              'module_code' => 'CP603', 'module_credit' => 10, 'semester' => 'Semester 2', 'nta_level' => '6', 'program_id' => 4],

            // Program 5 - Software Engineering
            ['module_name' => 'Requirements Engineering',      'module_code' => 'SE401', 'module_credit' => 10, 'semester' => 'Semester 1', 'nta_level' => '4', 'program_id' => 5],
            ['module_name' => 'Object Oriented Programming',   'module_code' => 'SE402', 'module_credit' => 10, 'semester' => 'Semester 1', 'nta_level' => '4', 'program_id' => 5],
            ['module_name' => 'Software Design Patterns',      'module_code' => 'SE501', 'module_credit' => 10, 'semester' => 'Semester 1', 'nta_level' => '5', 'program_id' => 5],
            ['module_name' => 'Testing and Quality Assurance', 'module_code' => 'SE502', 'module_credit' => 10, 'semester' => 'Semester 2', 'nta_level' => '5', 'program_id' => 5],
            ['module_name' => 'Agile Development',             'module_code' => 'SE503', 'module_credit' => 10, 'semester' => 'Semester 2', 'nta_level' => '5', 'program_id' => 5],
            ['module_name' => 'DevOps and CI/CD',              'module_code' => 'SE601', 'module_credit' => 10, 'semester' => 'Semester 1', 'nta_level' => '6', 'program_id' => 5],
            ['module_name' => 'Mobile Application Development','module_code' => 'SE602', 'module_credit' => 10, 'semester' => 'Semester 2', 'nta_level' => '6', 'program_id' => 5],
            ['module_name' => 'Capstone Project',              'module_code' => 'SE603', 'module_credit' => 10, 'semester' => 'Semester 2', 'nta_level' => '6', 'program_id' => 5],

            // ==================== BUSINESS ADMINISTRATION (dept 2) ====================
            // Program 6 - Diploma in Business Management
            ['module_name' => 'Principles of Management',      'module_code' => 'BM401', 'module_credit' => 10, 'semester' => 'Semester 1', 'nta_level' => '4', 'program_id' => 6],
            ['module_name' => 'Business Communication',        'module_code' => 'BM402', 'module_credit' => 10, 'semester' => 'Semester 1', 'nta_level' => '4', 'program_id' => 6],
            ['module_name' => 'Business Mathematics',          'module_code' => 'BM403', 'module_credit' => 10, 'semester' => 'Semester 2', 'nta_level' => '4', 'program_id' => 6],
            ['module_name' => 'Introduction to Economics',     'module_code' => 'BM404', 'module_credit' => 10, 'semester' => 'Semester 2', 'nta_level' => '4', 'program_id' => 6],
            ['module_name' => 'Financial Accounting',          'module_code' => 'BM501', 'module_credit' => 10, 'semester' => 'Semester 1', 'nta_level' => '5', 'program_id' => 6],
            ['module_name' => 'Human Resource Management',     'module_code' => 'BM502', 'module_credit' => 10, 'semester' => 'Semester 1', 'nta_level' => '5', 'program_id' => 6],
            ['module_name' => 'Marketing Management',          'module_code' => 'BM503', 'module_credit' => 10, 'semester' => 'Semester 2', 'nta_level' => '5', 'program_id' => 6],
            ['module_name' => 'Business Law',                  'module_code' => 'BM504', 'module_credit' => 10, 'semester' => 'Semester 2', 'nta_level' => '5', 'program_id' => 6],
            ['module_name' => 'Entrepreneurship Development',  'module_code' => 'BM601', 'module_credit' => 10, 'semester' => 'Semester 1', 'nta_level' => '6', 'program_id' => 6],
            ['module_name' => 'Strategic Management',          'module_code' => 'BM602', 'module_credit' => 10, 'semester' => 'Semester 2', 'nta_level' => '6', 'program_id' => 6],
            ['module_name' => 'Business Research Methods',     'module_code' => 'BM603', 'module_credit' => 10, 'semester' => 'Semester 2', 'nta_level' => '6', 'program_id' => 6],

            // Program 7 - Accounting and Finance
            ['module_name' => 'Principles of Accounting',      'module_code' => 'AF401', 'module_credit' => 10, 'semester' => 'Semester 1', 'nta_level' => '4', 'program_id' => 7],
            ['module_name' => 'Book Keeping',                  'module_code' => 'AF402', 'module_credit' => 10, 'semester' => 'Semester 2', 'nta_level' => '4', 'program_id' => 7],
            ['module_name' => 'Cost Accounting',               'module_code' => 'AF501', 'module_credit' => 10, 'semester' => 'Semester 1', 'nta_level' => '5', 'program_id' => 7],
            ['module_name' => 'Financial Management',          'module_code' => 'AF502', 'module_credit' => 10, 'semester' => 'Semester 2', 'nta_level' => '5', 'program_id' => 7],
            ['module_name' => 'Auditing',                      'module_code' => 'AF601', 'module_credit' => 10, 'semester' => 'Semester 1', 'nta_level' => '6', 'program_id' => 7],
            ['module_name' => 'Taxation',                      'module_code' => 'AF602', 'module_credit' => 10, 'semester' => 'Semester 2', 'nta_level' => '6', 'program_id' => 7],

            // Program 8 - Procurement and Supply Chain
            ['module_name' => 'Procurement Basics',            'module_code' => 'PS401', 'module_credit' => 10, 'semester' => 'Semester 1', 'nta_level' => '4', 'program_id' => 8],
            ['module_name' => 'Supply Chain Management',       'module_code' => 'PS501', 'module_credit' => 10, 'semester' => 'Semester 1', 'nta_level' => '5', 'program_id' => 8],
            ['module_name' => 'Inventory Management',          'module_code' => 'PS502', 'module_credit' => 10, 'semester' => 'Semester 2', 'nta_level' => '5', 'program_id' => 8],
            ['module_name' => 'Logistics Management',          'module_code' => 'PS601', 'module_credit' => 10, 'semester' => 'Semester 1', 'nta_level' => '6', 'program_id' => 8],
            ['module_name' => 'Contract Management',           'module_code' => 'PS602', 'module_credit' => 10, 'semester' => 'Semester 2', 'nta_level' => '6', 'program_id' => 8],

            // Program 9 - Human Resource Management
            ['module_name' => 'Introduction to HRM',           'module_code' => 'HR401', 'module_credit' => 10, 'semester' => 'Semester 1', 'nta_level' => '4', 'program_id' => 9],
            ['module_name' => 'Organizational Behavior',       'module_code' => 'HR501', 'module_credit' => 10, 'semester' => 'Semester 1', 'nta_level' => '5', 'program_id' => 9],
            ['module_name' => 'Training and Development',      'module_code' => 'HR502', 'module_credit' => 10, 'semester' => 'Semester 2', 'nta_level' => '5', 'program_id' => 9],
            ['module_name' => 'Labour Law',                    'module_code' => 'HR601', 'module_credit' => 10, 'semester' => 'Semester 1', 'nta_level' => '6', 'program_id' => 9],
            ['module_name' => 'Performance Management',        'module_code' => 'HR602', 'module_credit' => 10, 'semester' => 'Semester 2', 'nta_level' => '6', 'program_id' => 9],

            // Program 10 - Marketing Management
            ['module_name' => 'Consumer Behaviour',            'module_code' => 'MK401', 'module_credit' => 10, 'semester' => 'Semester 1', 'nta_level' => '4', 'program_id' => 10],
            ['module_name' => 'Digital Marketing',             'module_code' => 'MK501', 'module_credit' => 10, 'semester' => 'Semester 1', 'nta_level' => '5', 'program_id' => 10],
            ['module_name' => 'Brand Management',              'module_code' => 'MK502', 'module_credit' => 10, 'semester' => 'Semester 2', 'nta_level' => '5', 'program_id' => 10],
            ['module_name' => 'Sales Management',              'module_code' => 'MK601', 'module_credit' => 10, 'semester' => 'Semester 1', 'nta_level' => '6', 'program_id' => 10],
            ['module_name' => 'International Marketing',       'module_code' => 'MK602', 'module_credit' => 10, 'semester' => 'Semester 2', 'nta_level' => '6', 'program_id' => 10],

            // ==================== CIVIL ENGINEERING (dept 3) ====================
            // Program 11 - Civil Engineering Technology
            ['module_name' => 'Engineering Drawing',           'module_code' => 'CE401', 'module_credit' => 10, 'semester' => 'Semester 1', 'nta_level' => '4', 'program_id' => 11],
            ['module_name' => 'Construction Materials',        'module_code' => 'CE402', 'module_credit' => 10, 'semester' => 'Semester 1', 'nta_level' => '4', 'program_id' => 11],
            ['module_name' => 'Engineering Mathematics',       'module_code' => 'CE403', 'module_credit' => 10, 'semester' => 'Semester 2', 'nta_level' => '4', 'program_id' => 11],
            ['module_name' => 'Structural Analysis',           'module_code' => 'CE501', 'module_credit' => 10, 'semester' => 'Semester 1', 'nta_level' => '5', 'program_id' => 11],
            ['module_name' => 'Fluid Mechanics',               'module_code' => 'CE502', 'module_credit' => 10, 'semester' => 'Semester 1', 'nta_level' => '5', 'program_id' => 11],
            ['module_name' => 'Geotechnical Engineering',      'module_code' => 'CE503', 'module_credit' => 10, 'semester' => 'Semester 2', 'nta_level' => '5', 'program_id' => 11],
            ['module_name' => 'Hydraulics',                    'module_code' => 'CE504', 'module_credit' => 10, 'semester' => 'Semester 2', 'nta_level' => '5', 'program_id' => 11],
            ['module_name' => 'Advanced Structural Design',    'module_code' => 'CE601', 'module_credit' => 10, 'semester' => 'Semester 1', 'nta_level' => '6', 'program_id' => 11],
            ['module_name' => 'Transportation Engineering',    'module_code' => 'CE602', 'module_credit' => 10, 'semester' => 'Semester 2', 'nta_level' => '6', 'program_id' => 11],
            ['module_name' => 'Environmental Engineering',     'module_code' => 'CE603', 'module_credit' => 10, 'semester' => 'Semester 2', 'nta_level' => '6', 'program_id' => 11],

            // Program 12 - Construction Management
            ['module_name' => 'Construction Technology',       'module_code' => 'CM401', 'module_credit' => 10, 'semester' => 'Semester 1', 'nta_level' => '4', 'program_id' => 12],
            ['module_name' => 'Site Management',               'module_code' => 'CM501', 'module_credit' => 10, 'semester' => 'Semester 1', 'nta_level' => '5', 'program_id' => 12],
            ['module_name' => 'Construction Project Management','module_code' => 'CM502', 'module_credit' => 10, 'semester' => 'Semester 2', 'nta_level' => '5', 'program_id' => 12],
            ['module_name' => 'Quantity Surveying',            'module_code' => 'CM601', 'module_credit' => 10, 'semester' => 'Semester 1', 'nta_level' => '6', 'program_id' => 12],
            ['module_name' => 'Building Regulations',          'module_code' => 'CM602', 'module_credit' => 10, 'semester' => 'Semester 2', 'nta_level' => '6', 'program_id' => 12],

            // Program 13 - Building Technology
            ['module_name' => 'Masonry and Plastering',        'module_code' => 'BT401', 'module_credit' => 10, 'semester' => 'Semester 1', 'nta_level' => '4', 'program_id' => 13],
            ['module_name' => 'Carpentry and Joinery',         'module_code' => 'BT402', 'module_credit' => 10, 'semester' => 'Semester 2', 'nta_level' => '4', 'program_id' => 13],
            ['module_name' => 'Plumbing Technology',           'module_code' => 'BT501', 'module_credit' => 10, 'semester' => 'Semester 1', 'nta_level' => '5', 'program_id' => 13],
            ['module_name' => 'Electrical Installation',       'module_code' => 'BT502', 'module_credit' => 10, 'semester' => 'Semester 2', 'nta_level' => '5', 'program_id' => 13],
            ['module_name' => 'Building Services',             'module_code' => 'BT601', 'module_credit' => 10, 'semester' => 'Semester 1', 'nta_level' => '6', 'program_id' => 13],
            ['module_name' => 'Green Building Technology',     'module_code' => 'BT602', 'module_credit' => 10, 'semester' => 'Semester 2', 'nta_level' => '6', 'program_id' => 13],

            // Program 14 - Structural Engineering
            ['module_name' => 'Statics and Dynamics',          'module_code' => 'ST401', 'module_credit' => 10, 'semester' => 'Semester 1', 'nta_level' => '4', 'program_id' => 14],
            ['module_name' => 'Strength of Materials',         'module_code' => 'ST501', 'module_credit' => 10, 'semester' => 'Semester 1', 'nta_level' => '5', 'program_id' => 14],
            ['module_name' => 'Concrete Technology',           'module_code' => 'ST502', 'module_credit' => 10, 'semester' => 'Semester 2', 'nta_level' => '5', 'program_id' => 14],
            ['module_name' => 'Steel Structures',              'module_code' => 'ST601', 'module_credit' => 10, 'semester' => 'Semester 1', 'nta_level' => '6', 'program_id' => 14],
            ['module_name' => 'Foundation Engineering',        'module_code' => 'ST602', 'module_credit' => 10, 'semester' => 'Semester 2', 'nta_level' => '6', 'program_id' => 14],

            // Program 15 - Land Surveying
            ['module_name' => 'Introduction to Surveying',     'module_code' => 'LS401', 'module_credit' => 10, 'semester' => 'Semester 1', 'nta_level' => '4', 'program_id' => 15],
            ['module_name' => 'Topographic Surveying',         'module_code' => 'LS501', 'module_credit' => 10, 'semester' => 'Semester 1', 'nta_level' => '5', 'program_id' => 15],
            ['module_name' => 'Cadastral Surveying',           'module_code' => 'LS502', 'module_credit' => 10, 'semester' => 'Semester 2', 'nta_level' => '5', 'program_id' => 15],
            ['module_name' => 'Remote Sensing and GIS',        'module_code' => 'LS601', 'module_credit' => 10, 'semester' => 'Semester 1', 'nta_level' => '6', 'program_id' => 15],
            ['module_name' => 'Geodesy',                       'module_code' => 'LS602', 'module_credit' => 10, 'semester' => 'Semester 2', 'nta_level' => '6', 'program_id' => 15],

            // ==================== ELECTRICAL ENGINEERING (dept 4) ====================
            // Program 16 - Electrical Engineering Technology
            ['module_name' => 'Circuit Theory',                'module_code' => 'EE401', 'module_credit' => 10, 'semester' => 'Semester 1', 'nta_level' => '4', 'program_id' => 16],
            ['module_name' => 'Electronics I',                 'module_code' => 'EE402', 'module_credit' => 10, 'semester' => 'Semester 1', 'nta_level' => '4', 'program_id' => 16],
            ['module_name' => 'Electrical Measurements',       'module_code' => 'EE403', 'module_credit' => 10, 'semester' => 'Semester 2', 'nta_level' => '4', 'program_id' => 16],
            ['module_name' => 'Circuit Analysis',              'module_code' => 'EE501', 'module_credit' => 10, 'semester' => 'Semester 1', 'nta_level' => '5', 'program_id' => 16],
            ['module_name' => 'Digital Electronics',           'module_code' => 'EE502', 'module_credit' => 10, 'semester' => 'Semester 1', 'nta_level' => '5', 'program_id' => 16],
            ['module_name' => 'Control Systems',               'module_code' => 'EE503', 'module_credit' => 10, 'semester' => 'Semester 2', 'nta_level' => '5', 'program_id' => 16],
            ['module_name' => 'Electrical Machines',           'module_code' => 'EE504', 'module_credit' => 10, 'semester' => 'Semester 2', 'nta_level' => '5', 'program_id' => 16],
            ['module_name' => 'Power Systems',                 'module_code' => 'EE601', 'module_credit' => 10, 'semester' => 'Semester 1', 'nta_level' => '6', 'program_id' => 16],
            ['module_name' => 'Renewable Energy Systems',      'module_code' => 'EE602', 'module_credit' => 10, 'semester' => 'Semester 2', 'nta_level' => '6', 'program_id' => 16],
            ['module_name' => 'High Voltage Engineering',      'module_code' => 'EE603', 'module_credit' => 10, 'semester' => 'Semester 2', 'nta_level' => '6', 'program_id' => 16],

            // Program 17 - Electronics and Telecommunication
            ['module_name' => 'Analog Electronics',            'module_code' => 'ET401', 'module_credit' => 10, 'semester' => 'Semester 1', 'nta_level' => '4', 'program_id' => 17],
            ['module_name' => 'Signal Processing',             'module_code' => 'ET402', 'module_credit' => 10, 'semester' => 'Semester 2', 'nta_level' => '4', 'program_id' => 17],
            ['module_name' => 'Digital Communication Systems', 'module_code' => 'ET501', 'module_credit' => 10, 'semester' => 'Semester 1', 'nta_level' => '5', 'program_id' => 17],
            ['module_name' => 'Microprocessors and Embedded',  'module_code' => 'ET502', 'module_credit' => 10, 'semester' => 'Semester 2', 'nta_level' => '5', 'program_id' => 17],
            ['module_name' => 'Wireless Communication',        'module_code' => 'ET601', 'module_credit' => 10, 'semester' => 'Semester 1', 'nta_level' => '6', 'program_id' => 17],
            ['module_name' => 'Optical Fiber Communication',   'module_code' => 'ET602', 'module_credit' => 10, 'semester' => 'Semester 2', 'nta_level' => '6', 'program_id' => 17],

            // Program 18 - Power Engineering
            ['module_name' => 'Power Generation',              'module_code' => 'PE401', 'module_credit' => 10, 'semester' => 'Semester 1', 'nta_level' => '4', 'program_id' => 18],
            ['module_name' => 'Power Transmission',            'module_code' => 'PE501', 'module_credit' => 10, 'semester' => 'Semester 1', 'nta_level' => '5', 'program_id' => 18],
            ['module_name' => 'Power Distribution',            'module_code' => 'PE502', 'module_credit' => 10, 'semester' => 'Semester 2', 'nta_level' => '5', 'program_id' => 18],
            ['module_name' => 'Industrial Power Systems',      'module_code' => 'PE601', 'module_credit' => 10, 'semester' => 'Semester 1', 'nta_level' => '6', 'program_id' => 18],
            ['module_name' => 'Smart Grid Technology',         'module_code' => 'PE602', 'module_credit' => 10, 'semester' => 'Semester 2', 'nta_level' => '6', 'program_id' => 18],

            // Program 19 - Instrumentation and Control
            ['module_name' => 'Instrumentation Basics',        'module_code' => 'IC401', 'module_credit' => 10, 'semester' => 'Semester 1', 'nta_level' => '4', 'program_id' => 19],
            ['module_name' => 'Process Control',               'module_code' => 'IC501', 'module_credit' => 10, 'semester' => 'Semester 1', 'nta_level' => '5', 'program_id' => 19],
            ['module_name' => 'PLC Programming',               'module_code' => 'IC502', 'module_credit' => 10, 'semester' => 'Semester 2', 'nta_level' => '5', 'program_id' => 19],
            ['module_name' => 'Industrial Automation',         'module_code' => 'IC601', 'module_credit' => 10, 'semester' => 'Semester 1', 'nta_level' => '6', 'program_id' => 19],
            ['module_name' => 'SCADA Systems',                 'module_code' => 'IC602', 'module_credit' => 10, 'semester' => 'Semester 2', 'nta_level' => '6', 'program_id' => 19],

            // Program 20 - Renewable Energy Technology
            ['module_name' => 'Solar Energy Technology',       'module_code' => 'RE401', 'module_credit' => 10, 'semester' => 'Semester 1', 'nta_level' => '4', 'program_id' => 20],
            ['module_name' => 'Wind Energy Technology',        'module_code' => 'RE501', 'module_credit' => 10, 'semester' => 'Semester 1', 'nta_level' => '5', 'program_id' => 20],
            ['module_name' => 'Biomass Energy',                'module_code' => 'RE502', 'module_credit' => 10, 'semester' => 'Semester 2', 'nta_level' => '5', 'program_id' => 20],
            ['module_name' => 'Energy Storage Systems',        'module_code' => 'RE601', 'module_credit' => 10, 'semester' => 'Semester 1', 'nta_level' => '6', 'program_id' => 20],
            ['module_name' => 'Energy Audit and Management',   'module_code' => 'RE602', 'module_credit' => 10, 'semester' => 'Semester 2', 'nta_level' => '6', 'program_id' => 20],

            // ==================== MECHANICAL ENGINEERING (dept 5) ====================
            // Program 21 - Mechanical Engineering Technology
            ['module_name' => 'Engineering Mechanics',         'module_code' => 'ME401', 'module_credit' => 10, 'semester' => 'Semester 1', 'nta_level' => '4', 'program_id' => 21],
            ['module_name' => 'Workshop Technology',           'module_code' => 'ME402', 'module_credit' => 10, 'semester' => 'Semester 2', 'nta_level' => '4', 'program_id' => 21],
            ['module_name' => 'Thermodynamics',                'module_code' => 'ME501', 'module_credit' => 10, 'semester' => 'Semester 1', 'nta_level' => '5', 'program_id' => 21],
            ['module_name' => 'Fluid Power Systems',           'module_code' => 'ME502', 'module_credit' => 10, 'semester' => 'Semester 1', 'nta_level' => '5', 'program_id' => 21],
            ['module_name' => 'Manufacturing Processes',       'module_code' => 'ME503', 'module_credit' => 10, 'semester' => 'Semester 2', 'nta_level' => '5', 'program_id' => 21],
            ['module_name' => 'Fluid Mechanics',               'module_code' => 'ME601', 'module_credit' => 10, 'semester' => 'Semester 1', 'nta_level' => '6', 'program_id' => 21],
            ['module_name' => 'Machine Design',                'module_code' => 'ME602', 'module_credit' => 10, 'semester' => 'Semester 1', 'nta_level' => '6', 'program_id' => 21],
            ['module_name' => 'Heat Transfer',                 'module_code' => 'ME603', 'module_credit' => 10, 'semester' => 'Semester 2', 'nta_level' => '6', 'program_id' => 21],
            ['module_name' => 'Vibration Analysis',            'module_code' => 'ME604', 'module_credit' => 10, 'semester' => 'Semester 2', 'nta_level' => '6', 'program_id' => 21],

            // Program 22 - Industrial Maintenance
            ['module_name' => 'Maintenance Management',        'module_code' => 'IM401', 'module_credit' => 10, 'semester' => 'Semester 1', 'nta_level' => '4', 'program_id' => 22],
            ['module_name' => 'Lubrication Technology',        'module_code' => 'IM501', 'module_credit' => 10, 'semester' => 'Semester 1', 'nta_level' => '5', 'program_id' => 22],
            ['module_name' => 'Condition Monitoring',          'module_code' => 'IM502', 'module_credit' => 10, 'semester' => 'Semester 2', 'nta_level' => '5', 'program_id' => 22],
            ['module_name' => 'Predictive Maintenance',        'module_code' => 'IM601', 'module_credit' => 10, 'semester' => 'Semester 1', 'nta_level' => '6', 'program_id' => 22],
            ['module_name' => 'Plant Maintenance Systems',     'module_code' => 'IM602', 'module_credit' => 10, 'semester' => 'Semester 2', 'nta_level' => '6', 'program_id' => 22],

            // Program 23 - Production Engineering
            ['module_name' => 'Production Planning',           'module_code' => 'PR401', 'module_credit' => 10, 'semester' => 'Semester 1', 'nta_level' => '4', 'program_id' => 23],
            ['module_name' => 'Quality Control',               'module_code' => 'PR501', 'module_credit' => 10, 'semester' => 'Semester 1', 'nta_level' => '5', 'program_id' => 23],
            ['module_name' => 'CNC Machining',                 'module_code' => 'PR502', 'module_credit' => 10, 'semester' => 'Semester 2', 'nta_level' => '5', 'program_id' => 23],
            ['module_name' => 'Lean Manufacturing',            'module_code' => 'PR601', 'module_credit' => 10, 'semester' => 'Semester 1', 'nta_level' => '6', 'program_id' => 23],
            ['module_name' => 'CAD/CAM Systems',               'module_code' => 'PR602', 'module_credit' => 10, 'semester' => 'Semester 2', 'nta_level' => '6', 'program_id' => 23],

            // Program 24 - Welding and Fabrication
            ['module_name' => 'Welding Fundamentals',          'module_code' => 'WF401', 'module_credit' => 10, 'semester' => 'Semester 1', 'nta_level' => '4', 'program_id' => 24],
            ['module_name' => 'Arc Welding Technology',        'module_code' => 'WF501', 'module_credit' => 10, 'semester' => 'Semester 1', 'nta_level' => '5', 'program_id' => 24],
            ['module_name' => 'Metal Fabrication',             'module_code' => 'WF502', 'module_credit' => 10, 'semester' => 'Semester 2', 'nta_level' => '5', 'program_id' => 24],
            ['module_name' => 'Non-Destructive Testing',       'module_code' => 'WF601', 'module_credit' => 10, 'semester' => 'Semester 1', 'nta_level' => '6', 'program_id' => 24],
            ['module_name' => 'Structural Welding',            'module_code' => 'WF602', 'module_credit' => 10, 'semester' => 'Semester 2', 'nta_level' => '6', 'program_id' => 24],

            // Program 25 - HVAC Technology
            ['module_name' => 'Refrigeration Fundamentals',    'module_code' => 'HV401', 'module_credit' => 10, 'semester' => 'Semester 1', 'nta_level' => '4', 'program_id' => 25],
            ['module_name' => 'Air Conditioning Systems',      'module_code' => 'HV501', 'module_credit' => 10, 'semester' => 'Semester 1', 'nta_level' => '5', 'program_id' => 25],
            ['module_name' => 'Ventilation Design',            'module_code' => 'HV502', 'module_credit' => 10, 'semester' => 'Semester 2', 'nta_level' => '5', 'program_id' => 25],
            ['module_name' => 'HVAC Controls and Automation',  'module_code' => 'HV601', 'module_credit' => 10, 'semester' => 'Semester 1', 'nta_level' => '6', 'program_id' => 25],
            ['module_name' => 'Energy Efficient HVAC Design',  'module_code' => 'HV602', 'module_credit' => 10, 'semester' => 'Semester 2', 'nta_level' => '6', 'program_id' => 25],

            // ==================== AUTOMOTIVE ENGINEERING (dept 6) ====================
            // Program 26 - Automotive Engineering Technology
            ['module_name' => 'Auto Mechanics Basics',         'module_code' => 'AE401', 'module_credit' => 10, 'semester' => 'Semester 1', 'nta_level' => '4', 'program_id' => 26],
            ['module_name' => 'Engine Systems',                'module_code' => 'AE402', 'module_credit' => 10, 'semester' => 'Semester 1', 'nta_level' => '4', 'program_id' => 26],
            ['module_name' => 'Vehicle Electronics',           'module_code' => 'AE403', 'module_credit' => 10, 'semester' => 'Semester 2', 'nta_level' => '4', 'program_id' => 26],
            ['module_name' => 'Automotive Engines',            'module_code' => 'AE501', 'module_credit' => 10, 'semester' => 'Semester 1', 'nta_level' => '5', 'program_id' => 26],
            ['module_name' => 'Vehicle Technology',            'module_code' => 'AE502', 'module_credit' => 10, 'semester' => 'Semester 1', 'nta_level' => '5', 'program_id' => 26],
            ['module_name' => 'Vehicle Maintenance',           'module_code' => 'AE503', 'module_credit' => 10, 'semester' => 'Semester 2', 'nta_level' => '5', 'program_id' => 26],
            ['module_name' => 'Automotive Diagnostics',        'module_code' => 'AE601', 'module_credit' => 10, 'semester' => 'Semester 1', 'nta_level' => '6', 'program_id' => 26],
            ['module_name' => 'Hybrid Vehicle Technology',     'module_code' => 'AE602', 'module_credit' => 10, 'semester' => 'Semester 2', 'nta_level' => '6', 'program_id' => 26],
            ['module_name' => 'Auto Electrical Systems',       'module_code' => 'AE603', 'module_credit' => 10, 'semester' => 'Semester 2', 'nta_level' => '6', 'program_id' => 26],

            // Program 27 - Automotive Maintenance
            ['module_name' => 'Preventive Maintenance',        'module_code' => 'AM401', 'module_credit' => 10, 'semester' => 'Semester 1', 'nta_level' => '4', 'program_id' => 27],
            ['module_name' => 'Brake Systems',                 'module_code' => 'AM501', 'module_credit' => 10, 'semester' => 'Semester 1', 'nta_level' => '5', 'program_id' => 27],
            ['module_name' => 'Transmission Systems',          'module_code' => 'AM502', 'module_credit' => 10, 'semester' => 'Semester 2', 'nta_level' => '5', 'program_id' => 27],
            ['module_name' => 'Suspension and Steering',       'module_code' => 'AM601', 'module_credit' => 10, 'semester' => 'Semester 1', 'nta_level' => '6', 'program_id' => 27],
            ['module_name' => 'Advanced Diagnostics',          'module_code' => 'AM602', 'module_credit' => 10, 'semester' => 'Semester 2', 'nta_level' => '6', 'program_id' => 27],

            // Program 28 - Vehicle Body Repair
            ['module_name' => 'Panel Beating Basics',          'module_code' => 'VB401', 'module_credit' => 10, 'semester' => 'Semester 1', 'nta_level' => '4', 'program_id' => 28],
            ['module_name' => 'Vehicle Painting',              'module_code' => 'VB501', 'module_credit' => 10, 'semester' => 'Semester 1', 'nta_level' => '5', 'program_id' => 28],
            ['module_name' => 'Body Alignment and Repair',     'module_code' => 'VB502', 'module_credit' => 10, 'semester' => 'Semester 2', 'nta_level' => '5', 'program_id' => 28],
            ['module_name' => 'Advanced Body Repair',          'module_code' => 'VB601', 'module_credit' => 10, 'semester' => 'Semester 1', 'nta_level' => '6', 'program_id' => 28],
            ['module_name' => 'Vehicle Refinishing',           'module_code' => 'VB602', 'module_credit' => 10, 'semester' => 'Semester 2', 'nta_level' => '6', 'program_id' => 28],

            // Program 29 - Diesel Plant Mechanics
            ['module_name' => 'Diesel Engine Fundamentals',    'module_code' => 'DP401', 'module_credit' => 10, 'semester' => 'Semester 1', 'nta_level' => '4', 'program_id' => 29],
            ['module_name' => 'Fuel Injection Systems',        'module_code' => 'DP501', 'module_credit' => 10, 'semester' => 'Semester 1', 'nta_level' => '5', 'program_id' => 29],
            ['module_name' => 'Heavy Plant Equipment',         'module_code' => 'DP502', 'module_credit' => 10, 'semester' => 'Semester 2', 'nta_level' => '5', 'program_id' => 29],
            ['module_name' => 'Diesel Engine Overhaul',        'module_code' => 'DP601', 'module_credit' => 10, 'semester' => 'Semester 1', 'nta_level' => '6', 'program_id' => 29],
            ['module_name' => 'Plant Management',              'module_code' => 'DP602', 'module_credit' => 10, 'semester' => 'Semester 2', 'nta_level' => '6', 'program_id' => 29],

            // Program 30 - Hybrid and Electric Vehicles
            ['module_name' => 'Introduction to Electric Vehicles','module_code' => 'EV401', 'module_credit' => 10, 'semester' => 'Semester 1', 'nta_level' => '4', 'program_id' => 30],
            ['module_name' => 'Battery Technology',            'module_code' => 'EV501', 'module_credit' => 10, 'semester' => 'Semester 1', 'nta_level' => '5', 'program_id' => 30],
            ['module_name' => 'Electric Drivetrains',          'module_code' => 'EV502', 'module_credit' => 10, 'semester' => 'Semester 2', 'nta_level' => '5', 'program_id' => 30],
            ['module_name' => 'EV Charging Infrastructure',    'module_code' => 'EV601', 'module_credit' => 10, 'semester' => 'Semester 1', 'nta_level' => '6', 'program_id' => 30],
            ['module_name' => 'Advanced EV Systems',           'module_code' => 'EV602', 'module_credit' => 10, 'semester' => 'Semester 2', 'nta_level' => '6', 'program_id' => 30],
        ];

        // Add timestamps
        $now = now();
        $modules = array_map(function ($m) use ($now) {
            $m['created_at'] = $now;
            $m['updated_at'] = $now;
            return $m;
        }, $modules);

        DB::table('modules')->insert($modules);
        $this->command->info('✅ Modules seeded: ' . count($modules) . ' modules.');
    }
}
