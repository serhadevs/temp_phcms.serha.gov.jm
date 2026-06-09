<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ExamSiteSeeder extends Seeder
{
    public function run()
    {
        DB::table('exam_sites')->insert([
            ['id' =>  1, 'facility_id' => 1, 'name' => 'Spanish Town Hospital',                       'created_at' => null,                  'updated_at' => '2018-12-03 10:18:32', 'deleted_at' => '2018-12-03 10:18:32'],
            ['id' =>  2, 'facility_id' => 2, 'name' => 'Princess Margret Hospital',                   'created_at' => null,                  'updated_at' => null,                  'deleted_at' => null],
            ['id' =>  3, 'facility_id' => 3, 'name' => 'Glen Vincent Health Centre',                  'created_at' => null,                  'updated_at' => '2018-12-03 08:22:55', 'deleted_at' => null],
            ['id' =>  4, 'facility_id' => 3, 'name' => 'Kingston & St. Andrew Health Dept',           'created_at' => '2018-12-03 08:23:20', 'updated_at' => '2018-12-03 08:23:20', 'deleted_at' => null],
            ['id' =>  5, 'facility_id' => 2, 'name' => 'Morant Bay Health Centre',                    'created_at' => '2018-12-03 08:30:58', 'updated_at' => '2018-12-03 08:30:58', 'deleted_at' => null],
            ['id' =>  6, 'facility_id' => 1, 'name' => 'St Jago Park',                                'created_at' => '2018-12-03 10:12:04', 'updated_at' => '2018-12-03 10:12:04', 'deleted_at' => null],
            ['id' =>  7, 'facility_id' => 1, 'name' => 'Linstead',                                    'created_at' => '2018-12-03 10:18:46', 'updated_at' => '2018-12-03 10:18:46', 'deleted_at' => null],
            ['id' =>  8, 'facility_id' => 1, 'name' => 'GPHC',                                        'created_at' => '2018-12-03 10:19:07', 'updated_at' => '2018-12-03 10:19:07', 'deleted_at' => null],
            ['id' =>  9, 'facility_id' => 1, 'name' => 'OHHC',                                        'created_at' => '2018-12-03 10:19:31', 'updated_at' => '2018-12-03 10:19:31', 'deleted_at' => null],
            ['id' => 10, 'facility_id' => 1, 'name' => 'Bog Walk',                                    'created_at' => '2018-12-03 10:19:46', 'updated_at' => '2019-02-21 09:54:01', 'deleted_at' => null],
            ['id' => 11, 'facility_id' => 2, 'name' => "Yallahs Marie's Plaza",                       'created_at' => '2018-12-03 10:54:40', 'updated_at' => '2018-12-03 10:54:40', 'deleted_at' => null],
            ['id' => 12, 'facility_id' => 2, 'name' => 'Trinityville Health Centre',                  'created_at' => '2018-12-03 10:55:01', 'updated_at' => '2018-12-03 10:55:01', 'deleted_at' => null],
            ['id' => 13, 'facility_id' => 2, 'name' => 'Isaac Barrant Health Centre',                 'created_at' => '2018-12-03 10:55:20', 'updated_at' => '2018-12-03 10:55:20', 'deleted_at' => null],
            ['id' => 14, 'facility_id' => 2, 'name' => 'Seaforth Health  Centre',                     'created_at' => '2018-12-03 10:55:33', 'updated_at' => '2018-12-03 10:55:33', 'deleted_at' => null],
            ['id' => 15, 'facility_id' => 2, 'name' => 'Cedar Valley Health Center',                  'created_at' => '2018-12-03 10:55:58', 'updated_at' => '2018-12-03 10:55:58', 'deleted_at' => null],
            ['id' => 16, 'facility_id' => 2, 'name' => 'Llandewey Health Center',                     'created_at' => '2018-12-03 11:06:22', 'updated_at' => '2018-12-03 11:06:22', 'deleted_at' => null],
            ['id' => 17, 'facility_id' => 2, 'name' => 'Bath Health Centre',                          'created_at' => '2018-12-03 11:07:22', 'updated_at' => '2018-12-03 11:07:22', 'deleted_at' => null],
            ['id' => 18, 'facility_id' => 2, 'name' => 'Seaforth Health Centre',                      'created_at' => '2018-12-04 07:24:26', 'updated_at' => '2018-12-04 07:24:32', 'deleted_at' => '2018-12-04 07:24:32'],
            ['id' => 19, 'facility_id' => 1, 'name' => 'Onsite',                                      'created_at' => '2018-12-04 08:58:07', 'updated_at' => '2018-12-04 08:58:07', 'deleted_at' => '2019-02-12 15:48:11'],
            ['id' => 20, 'facility_id' => 2, 'name' => 'Onsite',                                      'created_at' => '2018-12-05 09:19:47', 'updated_at' => '2018-12-05 09:19:47', 'deleted_at' => '2019-02-12 15:48:11'],
            ['id' => 21, 'facility_id' => 1, 'name' => 'Kitson Town HC',                              'created_at' => '2018-12-05 15:30:51', 'updated_at' => '2018-12-05 15:31:41', 'deleted_at' => null],
            ['id' => 22, 'facility_id' => 1, 'name' => 'Ewarton HC',                                  'created_at' => '2018-12-05 15:31:14', 'updated_at' => '2018-12-05 15:31:14', 'deleted_at' => null],
            ['id' => 23, 'facility_id' => 1, 'name' => 'Sligoville HC',                               'created_at' => '2018-12-05 15:32:52', 'updated_at' => '2018-12-05 15:32:52', 'deleted_at' => null],
            ['id' => 24, 'facility_id' => 1, 'name' => 'Watermount HC',                               'created_at' => '2018-12-05 15:33:53', 'updated_at' => '2018-12-05 15:33:53', 'deleted_at' => null],
            ['id' => 25, 'facility_id' => 1, 'name' => 'Conners HC',                                  'created_at' => '2018-12-05 15:34:26', 'updated_at' => '2018-12-05 15:34:26', 'deleted_at' => null],
            ['id' => 26, 'facility_id' => 1, 'name' => 'Lluidas Vale HC',                             'created_at' => '2018-12-05 15:34:55', 'updated_at' => '2019-02-21 09:54:56', 'deleted_at' => null],
            ['id' => 27, 'facility_id' => 1, 'name' => 'Redwood HC',                                  'created_at' => '2018-12-05 15:35:57', 'updated_at' => '2018-12-05 15:35:57', 'deleted_at' => null],
            ['id' => 28, 'facility_id' => 1, 'name' => "Guy's Hill HC",                               'created_at' => '2018-12-05 15:36:34', 'updated_at' => '2018-12-10 12:12:09', 'deleted_at' => '2018-12-10 12:12:09'],
            ['id' => 29, 'facility_id' => 1, 'name' => 'Redwood HC',                                  'created_at' => '2018-12-10 12:08:50', 'updated_at' => '2018-12-10 12:10:09', 'deleted_at' => '2018-12-10 12:10:09'],
            ['id' => 30, 'facility_id' => 1, 'name' => 'Guys Hill HC',                                'created_at' => '2018-12-10 12:09:19', 'updated_at' => '2018-12-10 12:09:53', 'deleted_at' => '2018-12-10 12:09:53'],
            ['id' => 31, 'facility_id' => 1, 'name' => 'Guys Hill HC',                                'created_at' => '2018-12-10 12:12:40', 'updated_at' => '2018-12-10 12:12:40', 'deleted_at' => null],
            ['id' => 32, 'facility_id' => 1, 'name' => 'Riversdale HC',                               'created_at' => '2018-12-10 12:15:07', 'updated_at' => '2018-12-10 12:15:07', 'deleted_at' => null],
            ['id' => 33, 'facility_id' => 1, 'name' => 'Harkers Hall HC',                             'created_at' => '2018-12-10 12:15:34', 'updated_at' => '2018-12-10 12:15:34', 'deleted_at' => null],
            ['id' => 34, 'facility_id' => 1, 'name' => 'Kitson Town',                                 'created_at' => '2018-12-10 12:16:14', 'updated_at' => '2018-12-10 12:16:41', 'deleted_at' => '2018-12-10 12:16:41'],
            ['id' => 35, 'facility_id' => 1, 'name' => 'Glengoffe HC',                                'created_at' => '2018-12-10 12:17:47', 'updated_at' => '2018-12-10 12:17:47', 'deleted_at' => null],
            ['id' => 36, 'facility_id' => 1, 'name' => 'Point Hill HC',                               'created_at' => '2019-03-05 09:44:20', 'updated_at' => '2019-03-05 09:44:37', 'deleted_at' => null],
            ['id' => 37, 'facility_id' => 3, 'name' => 'National Chest Hospital Conference Room',     'created_at' => '2019-03-28 10:15:03', 'updated_at' => '2019-03-28 10:20:15', 'deleted_at' => null],
            ['id' => 38, 'facility_id' => 1, 'name' => 'Above Rocks',                                 'created_at' => '2019-05-27 07:36:13', 'updated_at' => '2019-05-27 07:37:10', 'deleted_at' => null],
            ['id' => 39, 'facility_id' => 1, 'name' => 'TROJA',                                       'created_at' => '2019-06-05 09:58:57', 'updated_at' => '2019-06-05 09:58:57', 'deleted_at' => null],
            ['id' => 40, 'facility_id' => 1, 'name' => 'GPHC',                                        'created_at' => '2019-08-05 12:42:04', 'updated_at' => '2019-08-05 12:42:27', 'deleted_at' => '2019-08-05 12:42:27'],
            ['id' => 41, 'facility_id' => 3, 'name' => 'Sir John Golding Rehabilitation Centre',      'created_at' => '2020-11-23 13:52:00', 'updated_at' => '2021-06-02 13:06:07', 'deleted_at' => '2021-06-02 13:06:07'],
            ['id' => 42, 'facility_id' => 3, 'name' => 'Maxfield Park Health Centre',                 'created_at' => '2021-03-08 08:07:40', 'updated_at' => '2022-11-22 12:38:52', 'deleted_at' => '2022-11-22 12:38:52'],
            ['id' => 43, 'facility_id' => 3, 'name' => 'Windward Road Health Centre',                 'created_at' => '2021-03-08 08:22:13', 'updated_at' => '2022-11-22 12:38:49', 'deleted_at' => '2022-11-22 12:38:49'],
            ['id' => 44, 'facility_id' => 2, 'name' => 'JOONG',                                       'created_at' => '2022-09-26 11:35:50', 'updated_at' => '2022-09-26 11:38:14', 'deleted_at' => '2022-09-26 11:38:14'],
            ['id' => 45, 'facility_id' => 3, 'name' => 'MICO UNIVERSITY COLLEGE (CHAPLE)',            'created_at' => '2022-11-22 12:39:20', 'updated_at' => '2022-11-23 10:26:21', 'deleted_at' => null],
            ['id' => 46, 'facility_id' => 1, 'name' => 'St. Jago Park',                               'created_at' => '2023-03-20 14:47:15', 'updated_at' => '2023-03-20 14:47:15', 'deleted_at' => null],
            ['id' => 47, 'facility_id' => 2, 'name' => 'ONSITE',                                      'created_at' => '2023-09-29 11:42:53', 'updated_at' => '2023-09-29 11:42:53', 'deleted_at' => null],
            ['id' => 48, 'facility_id' => 3, 'name' => 'Excelsior',                                   'created_at' => null,                  'updated_at' => null,                  'deleted_at' => null],
        ]);
    }
}
