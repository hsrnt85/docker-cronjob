<?php


$pageList = "Senarai ";
$pageEdit = "Kemaskini ";
$pageView = "Papar ";
$pageNew = "Daftar ";
$pageNewProcess = "Proses ";
$pageReview = "Semakan ";
$pageScore = "Maklumat ";
$pageIndexLetter = "Jana Surat ";
$pageUpload = "Muat Naik ";

//GET PARAM FROM URL
//sample => http://....?year=2023
//USED WHEN LEVEL > 3
$year = (isset($_REQUEST['year'])) ? $_REQUEST['year'] : "";
$month = (isset($_REQUEST['month'])) ? $_REQUEST['month'] : "";
$quarters_category_id = (isset($_REQUEST['qcid'])) ? $_REQUEST['qcid'] : "";

return [
    //prefix
    /* USER */
    'PenggunaSistem' => [
        'folder_path' => 'modules.SystemAdmin.User',
        'tree_level' => '1',
        'title' => 'Pengguna Sistem',
        'title_l1' => 'Pengguna Sistem',
        'lang_file' => 'user',
        'route_l1' => 'user.index',
        'user.index' => $pageList,
        'user.create' => $pageNew,
        'user.edit' => $pageEdit,
        'user.view' => $pageView,
    ],

    /* USER APPROVAL*/
    'PengaktifanAkaun' => [
        'folder_path' => 'modules.SystemAdmin.UserApproval',
        'tree_level' => '1',
        'title' => 'Pengaktifan Akaun',
        'title_l1' => 'Pengaktifan Akaun',
        'lang_file' => 'user-approval',
        'route_l1' => 'userApproval.index',
        'userApproval.index'=> $pageList,
        'userApproval.approval' => $pageEdit,
    ],

    /* USER POLICY */
    'PolisiPengguna' => [
        'folder_path' => 'modules.SystemAdmin.UserPolicy',
        'tree_level' => '1',
        'title' => 'Peranan',
        'title_l1' => 'Peranan',
        'lang_file' => 'user-policy',
        'route_l1' => 'userPolicy.index',
        'userPolicy.index'=> $pageList,
        'userPolicy.create' => $pageNew,
        'userPolicy.edit' => $pageEdit,
        'userPolicy.view' => $pageView,
    ],

    /* AGENCY */
    'Agensi' => [
        'folder_path' => 'modules.SystemConfiguration.Agency',
        'tree_level' => '1',
        'title' => 'Agensi',
        'title_l1' => 'Agensi',
        'lang_file' => 'agency',
        'route_l1' => 'agency.index',
        'agency.index'=> $pageList,
        'agency.edit' => $pageEdit,
        'agency.view' => $pageView,
    ],

    /* APPLICATION DATE */
    'TarikhPermohonan' => [
        'folder_path' => 'modules.SystemConfiguration.ApplicationDate',
        'tree_level' => '1',
        'title' => 'Tarikh Permohonan',
        'title_l1' => 'Tarikh Permohonan',
        'lang_file' => 'application-date',
        'route_l1' => 'applicationDate.index',
        'applicationDate.index'=> $pageList,
        'applicationDate.create' => $pageNew,
        'applicationDate.edit' => $pageEdit,
        'applicationDate.view' => $pageView,
    ],

    /* DEPARTMENT */
    'Jabatan' => [
        'folder_path' => 'modules.SystemConfiguration.Department',
        'tree_level' => '1',
        'title' => 'Jabatan',
        'title_l1' => 'Jabatan',
        'lang_file' => 'department',
        'route_l1' => 'department.index',
        'department.index'=> $pageList,
        'department.view' => $pageView,
    ],

    /* DOCUMENT */
    'Dokumen' => [
        'folder_path' => 'modules.SystemConfiguration.Document',
        'tree_level' => '1',
        'title' => 'Dokumen',
        'title_l1' => 'Dokumen',
        'lang_file' => 'document',
        'route_l1' => 'document.index',
        'document.index'=> $pageList,
        'document.create' => $pageNew,
        'document.edit' => $pageEdit,
        'document.view' => $pageView,
    ],

    /* INVENTORY RESPONSIBILITY */
    'TanggungjawabInventori' => [
        'folder_path' => 'modules.SystemConfiguration.InventoryResponsibility',
        'tree_level' => '1',
        'title' => 'Jabatan Bertanggungjawab (Inventori)',
        'title_l1' => 'Jabatan Bertanggungjawab (Inventori)',
        'lang_file' => 'inventory-responsibility',
        'route_l1' => 'inventoryResponsibility.index',
        'inventoryResponsibility.index'=> $pageList,
        'inventoryResponsibility.create' => $pageNew,
        'inventoryResponsibility.edit' => $pageEdit,
        'inventoryResponsibility.view' => $pageView,
    ],

    /* INVENTORY  (ADA NESTED LIST PAGE)*/
    'Inventori' => [
        'folder_path' => 'modules.SystemConfiguration.Inventory',
        'tree_level' => '2',
        'title' => 'Inventori',
        'title_l1' => 'Kategori Kuarters (Lokasi)',
        'title_l2' => 'Inventori',
        'lang_file' => 'inventory',
        'route_l1' => 'listQuartersCategoryInventory.index',
        'route_l2' => 'inventory.index',
        'listQuartersCategoryInventory.index' => [ 'l1' => $pageList ],
        'inventory.index' => [ 'l1' => $pageList, 'l2' => $pageList ],
        'inventory.create' => [ 'l1' => $pageList, 'l2' => $pageNew ],
        'inventory.edit' => [ 'l1' => $pageList, 'l2' => $pageEdit ],
        'inventory.view' => [ 'l1' => $pageList, 'l2' => $pageView ],
    ],

    /* OFFICER */
    'Pegawai' => [
        'folder_path' => 'modules.SystemConfiguration.Officer',
        'tree_level' => '1',
        'title' => 'Pegawai',
        'title_l1' => 'Pegawai',
        'lang_file' => 'officer',
        'route_l1' => 'officer.index',
        'officer.index'=> $pageList,
        'officer.create' => $pageNew,
        'officer.edit' => $pageEdit,
        'officer.view' => $pageView,
    ],

    /* POSITION */
    'Jawatan' => [
        'folder_path' => 'modules.SystemConfiguration.Position',
        'tree_level' => '1',
        'title' => 'Jawatan',
        'title_l1' => 'Jawatan',
        'lang_file' => 'position',
        'route_l1' => 'position.index',
        'position.index'=> $pageList,
        'position.create' => $pageNew,
        'position.edit' => $pageEdit,
        'position.view' => $pageView,
    ],

    /* POSITION GRADE */
    'GredJawatan' => [
        'folder_path' => 'modules.SystemConfiguration.PositionGrade',
        'tree_level' => '1',
        'title' => 'Gred Jawatan',
        'title_l1' => 'Gred Jawatan',
        'lang_file' => 'position-grade',
        'route_l1' => 'positionGrade.index',
        'positionGrade.index'=> $pageList,
        'positionGrade.create' => $pageNew,
        'positionGrade.edit' => $pageEdit,
        'positionGrade.view' => $pageView,
    ],

    /* POSITION GRADE TYPE */
    'KodJawatan' => [
        'folder_path' => 'modules.SystemConfiguration.PositionGradeType',
        'tree_level' => '1',
        'title' => 'Kod Jawatan',
        'title_l1' => 'Kod Jawatan',
        'lang_file' => 'position-grade-type',
        'route_l1' => 'positionGradeType.index',
        'positionGradeType.index'=> $pageList,
        'positionGradeType.create' => $pageNew,
        'positionGradeType.edit' => $pageEdit,
        'positionGradeType.view' => $pageView,
    ],

    /* QUARTERS CATEGORY */
    'LokasiKuarters' => [
        'folder_path' => 'modules.SystemConfiguration.QuartersCategory',
        'tree_level' => '1',
        'title' => 'Kategori Kuarters (Lokasi)',
        'title_l1' => 'Kategori Kuarters (Lokasi)',
        'lang_file' => 'quarters-category',
        'route_l1' => 'quartersCategory.index',
        'quartersCategory.index'=> $pageList,
        'quartersCategory.create' => $pageNew,
        'quartersCategory.edit' => $pageEdit,
        'quartersCategory.view' => $pageView,
    ],

    /* QUARTERS CLASS */
    'KelasKuarters' => [
        'folder_path' => 'modules.SystemConfiguration.QuartersClass',
        'tree_level' => '1',
        'title' => 'Kelas Kuarters',
        'title_l1' => 'Kelas Kuarters',
        'lang_file' => 'quarters-class',
        'route_l1' => 'quartersClass.index',
        'quartersClass.index'=> $pageList,
        'quartersClass.create' => $pageNew,
        'quartersClass.edit' => $pageEdit,
        'quartersClass.view' => $pageView,
    ],

    /* QUARTERS OPTION */
    'BilanganPilihanLokasiKuarters' => [
        'folder_path' => 'modules.SystemConfiguration.QuartersOption',
        'tree_level' => '1',
        'title' => 'Bilangan Pilihan Kategori Kuarters (Lokasi)',
        'title_l1' => 'Bilangan Pilihan Kategori Kuarters (Lokasi)',
        'lang_file' => 'quarters-option',
        'route_l1' => 'quartersOption.index',
        'quartersOption.index'=> $pageList,
        'quartersOption.create' => $pageNew,
        'quartersOption.edit' => $pageEdit,
        'quartersOption.view' => $pageView,
    ],

    /* RADIUS */
    'Radius' => [
        'folder_path' => 'modules.SystemConfiguration.Radius',
        'tree_level' => '1',
        'title' => 'Radius',
        'title_l1' => 'Radius',
        'lang_file' => 'radius',
        'route_l1' => 'radius.index',
        'radius.index'=> $pageList,
        'radius.create' => $pageNew,
        'radius.edit' => $pageEdit,
        'radius.view' => $pageView,
    ],

    /* CRON JOB */
    'CronJob' => [
        'folder_path' => 'modules.SystemConfiguration.CronJob',
        'tree_level' => '1',
        'title' => 'Cron Job',
        'title_l1' => 'Cron Job',
        'lang_file' => 'cron-job',
        'route_l1' => 'cronJob.index',
        'cronJob.index'=> $pageList,
        'cronJob.create' => $pageNewProcess,
    ],

    /* SPECIAL PERMISSION */
    'KebenaranKhas' => [
        'folder_path' => 'modules.SystemConfiguration.SpecialPermission',
        'tree_level' => '1',
        'title' => 'Kebenaran Khas',
        'title_l1' => 'Kebenaran Khas',
        'lang_file' => 'special-permission',
        'route_l1' => 'specialPermission.index',
        'specialPermission.index'=> $pageList,
        'specialPermission.create' => $pageNew,
        'specialPermission.edit' => $pageEdit,
        'specialPermission.view' => $pageView,
    ],

    /* QUARTERS (ADA NESTED LIST PAGE & NESTED ADD PAGE)*/
    'Kuarters' => [
        'folder_path' => 'modules.QuartersInfo.Quarters',
        'tree_level' => '2',
        'title' => 'Maklumat Kuarters',
        'title_l1' => 'Kategori Kuarters (Lokasi)',
        'title_l2' => 'Kuarters',
        'lang_file' => 'quarters',
        'route_l1' => 'listQuartersCategory.index',
        'route_l2' => 'quarters.index',
        'listQuartersCategory.index' => [ 'l1' => $pageList ],
        'quarters.index' => [  'l1' => $pageList, 'l2' => $pageList ],
        'quarters.create' => [  'l1' => $pageList, 'l2' => $pageNew ],
        'quarters.addUnitNo' => [  'l1' => $pageList, 'l2' => $pageNew ],
        'quarters.edit' => [  'l1' => $pageList, 'l2' => $pageEdit ],
        'quarters.view' => [  'l1' => $pageList, 'l2' => $pageView ],
    ],
    /* END QUARTERS (ADA NESTED LIST PAGE & NESTED ADD PAGE)*/

    /* APPLICATION SCORING CRITERIA */
    'KriteriaPemarkahan' => [
        'folder_path' => 'modules.ApplicationReview.ApplicationScoringCriteria',
        'tree_level' => '1',
        'title' => 'Kriteria Pemarkahan',
        'title_l1' => 'Kriteria Pemarkahan',
        'lang_file' => 'application-scoring-criteria',
        'route_l1' => 'applicationScoringCriteria.index',
        'applicationScoringCriteria.index'=> $pageList,
        'applicationScoringCriteria.create' => $pageNew,
        'applicationScoringCriteria.edit' => $pageEdit,
        'applicationScoringCriteria.view' => $pageView,
    ],

    /* APPLICATION SCORING (ADA SCORE PAGE)*/
    'PemarkahanPermohonan' => [
        'folder_path' => 'modules.ApplicationReview.ApplicationScoring',
        'tree_level' => '1',
        'title' => 'Penilaian Permohonan',
        'title_l1' => 'Penilaian Permohonan',
        'lang_file' => 'application-scoring',
        'route_l1' => 'applicationScoring.index',
        'applicationScoring.index'=> $pageList,
        'applicationScoring.edit' => $pageEdit,
        'applicationScoring.view' => $pageView,
        'applicationScoring.score' => $pageScore,
    ],

    /* APPLICATION REVIEW (ADA REVIEW PAGE)*/
    'SemakanPermohonan' => [
        'folder_path' => 'modules.ApplicationReview.ApplicationReview',
        'tree_level' => '1',
        'title' => 'Semakan Permohonan',
        'title_l1' => 'Semakan Permohonan',
        'lang_file' => 'application-review',
        'route_l1' => 'applicationReview.index',
        'applicationReview.index'=> $pageList,
        'applicationReview.edit' => $pageEdit,
        'applicationReview.view' => $pageView,
        'applicationReview.review' => $pageEdit,
    ],

    /* APPLICATION APPROVAL */
    'KelulusanPermohonan' => [
        'folder_path' => 'modules.ApplicationReview.ApplicationApproval',
        'tree_level' => '1',
        'title' => 'Kelulusan Permohonan',
        'title_l1' => 'Kelulusan Permohonan',
        'lang_file' => 'application-approval',
        'route_l1' => 'applicationApproval.index',
        'applicationApproval.index'=> $pageList,
        'applicationApproval.edit' => $pageEdit,
        'applicationApproval.view' => $pageView,
    ],

    /* INVITATION PANEL */
    'PanelLuar' => [
        'folder_path' => 'modules.Meeting.InvitationPanel',
        'tree_level' => '1',
        'title' => 'Panel Luar',
        'title_l1' => 'Panel Luar',
        'lang_file' => 'invitation-panel',
        'route_l1' => 'invitationPanel.index',
        'invitationPanel.index'=> $pageList,
        'invitationPanel.create' => $pageNew,
        'invitationPanel.edit' => $pageEdit,
        'invitationPanel.view' => $pageView,
    ],

    /* MEETING REGISTRATION (ADA PAGE INDEX LETTER) */
    'DaftarMesyuarat' => [
        'folder_path' => 'modules.Meeting.MeetingRegistration',
        'tree_level' => '1',
        'title' => 'Daftar Mesyuarat',
        'title_l1' => 'Daftar Mesyuarat',
        'lang_file' => 'meeting-registration',
        'route_l1' => 'meetingRegistration.index',
        'meetingRegistration.index'=> $pageList,
        'meetingRegistration.create' => '',
        'meetingRegistration.edit' => $pageEdit,
        'meetingRegistration.view' => $pageView,
        'meetingRegistration.indexLetter' => $pageIndexLetter,
    ],

    /* EVALUATION MEETING */
    'MesyuaratPenilaian' => [
        'folder_path' => 'modules.Meeting.EvaluationMeeting',
        'tree_level' => '1',
        'title' => 'Mesyuarat Jawatankuasa',
        'title_l1' => 'Mesyuarat Jawatankuasa',
        'lang_file' => 'evaluation-meeting',
        'route_l1' => 'evaluationMeeting.index',
        'evaluationMeeting.index'=> $pageList,
        'evaluationMeeting.edit' => $pageEdit,
        'evaluationMeeting.view' => $pageView,
        'evaluationMeeting.ajaxGetApplicationById' => ''
    ],

    /* PLACEMENT (ADA NESTED LIST PAGE)*/
    'Penempatan' => [
        'folder_path' => 'modules.Placement.Placement',
        'tree_level' => '2',
        'title' => 'Penempatan',
        'title_l1' => 'Kategori Kuarters (Lokasi)',
        'title_l2' => 'Penempatan Kuarters',
        'lang_file' => 'placement',
        'route_l1' => 'placement.index',
        'route_l2' => 'placement.listPlacement',
        'placement.index' => [ 'l1' => $pageList ],
        'placement.listPlacement' => [  'l1' => $pageList, 'l2' => $pageList ],
        'placement.bulkPlacement' => [  'l1' => $pageList, 'l2' => $pageList ],
        'placement.show' => [  'l1' => $pageList, 'l2' => $pageView ],
        'placement.edit' => [  'l1' => $pageList, 'l2' => $pageEdit ],
    ],

    /* QUARTERS ACCEPTANCE*/
    'TerimaTawaran' => [
        'folder_path' => 'modules.Placement.Accept',
        'tree_level' => '2',
        'title' => 'Senarai Terima Tawaran',
        'title_l1' => 'Senarai Terima Tawaran',
        'title_l2' => 'Maklumat Permohonan',
        'lang_file' => 'placement',
        'route_l1' => 'accept.index',
        'route_l2' => 'accept.show',
        'accept.index'=> [  'l1' => ''],
        'accept.show' => [  'l1' => '', 'l2' => $pageView ],
    ],

    /* QUARTERS REJECTION*/
    'TolakTawaran' => [
        'folder_path' => 'modules.Placement.Reject',
        'tree_level' => '2',
        'title' => 'Senarai Tolak Tawaran',
        'title_l1' => 'Senarai Tolak Tawaran',
        'title_l2' => 'Maklumat Permohonan',
        'lang_file' => 'placement',
        'route_l1' => 'reject.index',
        'route_l2' => 'reject.show',
        'reject.index'=> [  'l1' => ''],
        'reject.show' => [  'l1' => '', 'l2' => $pageView ],
    ],

    /* COMPLAINT APPOINTMENT */
    'TemujanjiAduan' => [
        'folder_path' => 'modules.Monitoring.ComplaintAppointment',
        'tree_level' => '1',
        'title' => 'Temujanji Aduan',
        'title_l1' => 'Temujanji Aduan',
        'lang_file' => 'complaint-appointment',
        'route_l1' => 'complaintAppointment.index',
        'complaintAppointment.index'=> $pageList,
        'complaintAppointment.create' => $pageEdit,//.'(Aduan Baru)',
        'complaintAppointment.edit' => $pageEdit,
        'complaintAppointment.view' => $pageView,
    ],

    /* COMPLAINT MONITORING */
    'PemantauanAduan' => [
        'folder_path' => 'modules.Monitoring.ComplaintMonitoring',
        'tree_level' => '1',
        'title' => 'Pemantauan Aduan',
        'title_l1' => 'Pemantauan Aduan',
        'lang_file' => 'complaint-monitoring',
        'route_l1' => 'complaintMonitoring.index',
        'complaintMonitoring.index'=> $pageList,
        'complaintMonitoring.edit'=> $pageEdit,//.'(Pengesahan Temujanji)',
        'complaintMonitoring.view_aduan_selesai'=> $pageView,
        'complaintMonitoring.view_aduan_ditolak'=> $pageView,
        'complaintMonitoring.view_aduan_berulang'=> $pageView,
        'complaintMonitoring.view_aduan_selenggara'=> $pageView,
        'complaintMonitoring.view_penghuni_keluar'=> $pageView,
    ],

    /* RULES VIOLATION COMPLAINT APPROVAL */
    'PengesahanAduanAwam' => [
        'folder_path' => 'modules.Monitoring.RulesViolationComplaintApproval',
        'tree_level' => '1',
        'title' => 'Pengesahan Aduan Awam',
        'title_l1' => 'Pengesahan Aduan Awam',
        'lang_file' => 'rules-violation-complaint-approval',
        'route_l1' => 'rulesViolationComplaintApproval.index',
        'rulesViolationComplaintApproval.index'=> $pageList,
        'rulesViolationComplaintApproval.create' => $pageEdit,//.'(Aduan Baru)',
        'rulesViolationComplaintApproval.edit' => $pageEdit,
        'rulesViolationComplaintApproval.view' => $pageView,
    ],

    /* ROUTINE MONITORING RECORD*/
    'RekodPemantauanBerkala' => [
        'folder_path' => 'modules.RoutineMonitoring.RoutineInspectionRecord',
        'tree_level' => '2',
        'title' => 'Rekod Pemantauan Berkala',
        'title_l1' => 'Kategori Kuarters (Lokasi)',
        'title_l2' => 'Pemantauan Berkala',
        'lang_file' => 'routine-inspection-record',
        'route_l1' => 'routineInspectionRecord.listLocation',
        'route_l2' => 'routineInspectionRecord.listInspection',
        'routineInspectionRecord.listLocation' => [ 'l1' => $pageList ],
        'routineInspectionRecord.listInspection' => [ 'l1' => $pageList, 'l2' => $pageList ],
        'routineInspectionRecord.create' => [ 'l1' => $pageList, 'l2' => $pageNew ],
        'routineInspectionRecord.edit' => [ 'l1' => $pageList, 'l2' => $pageEdit ],
        'routineInspectionRecord.view' => [ 'l1' => $pageList, 'l2' => $pageView ],
    ],

    /* ROUTINE MONITORING APPROVAL*/
    'PengesahanPemantauanBerkala' => [
        'folder_path' => 'modules.RoutineMonitoring.RoutineInspectionApproval',
        'tree_level' => '2',
        'title' => 'Pengesahan Pemantauan Berkala',
        'title_l1' => 'Pemantauan Berkala',
        'title_l2' => 'Pemantauan Berkala',
        'lang_file' => 'routine-inspection-approval',
        'route_l1' => 'routineInspectionApproval.index',
        'routineInspectionApproval.index' => [ 'l1' => $pageList ],
        'routineInspectionApproval.edit' => [ 'l1' => $pageList, 'l2' => $pageEdit ],
        'routineInspectionApproval.view' => [ 'l1' => $pageList, 'l2' => $pageView ],
    ],

    /* ROUTINE MONITORING TRANSACTION*/
    'TransaksiPemantauanBerkala' => [
        'folder_path' => 'modules.RoutineMonitoring.RoutineInspectionTransaction',
        'tree_level' => '1',
        'title' => 'Transaksi Pemantauan Berkala',
        'title_l1' => 'Transaksi Pemantauan Berkala',
        'lang_file' => 'routine-inspection-transaction',
        'route_l1' => 'routineInspectionTransaction.index',
        'routineInspectionTransaction.index'=> $pageList,
        'routineInspectionTransaction.create' => $pageNew,
        'routineInspectionTransaction.edit' => $pageEdit,
        'routineInspectionTransaction.view' => $pageView,
    ],

    /* JADUAL PEMANTAUAN BERKALA*/
    'JadualPemantauanBerkala' => [
        'folder_path' => 'modules.RoutineMonitoring.RoutineInspectionSchedule',
        'tree_level' => '1',
        'title' => 'Jadual Pemantauan Berkala',
        'title_l1' => 'Jadual Pemantauan Berkala',
        'lang_file' => 'routine-inspection-schedule',
        'route_l1' => 'routineInspectionSchedule.index',
        'routineInspectionSchedule.index'=> $pageList,
        'routineInspectionSchedule.create' => $pageNew,
        'routineInspectionSchedule.edit' => $pageEdit,
        'routineInspectionSchedule.view' => $pageView,
    ],

    /* TENANT (ADA NESTED LIST PAGE) */
    'Penghuni' => [
        'folder_path' => 'modules.Tenant.Tenant',
        'tree_level' => '2',
        'title' => 'Penghuni',
        'title_l1' => 'Kategori Kuarters (Lokasi)',
        'title_l2' => 'Penghuni',
        'lang_file' => 'tenant',
        'route_l1' => 'tenant.index',
        'route_l2' => 'tenant.tenantList',
        'tenant.index' => [ 'l1' => $pageList ],
        'tenant.tenantList' => [  'l1' => $pageList, 'l2' => $pageList ],
        'tenant.view' => [  'l1' => $pageList, 'l2' => $pageView ],
        'tenant.leaveApproval' => [  'l1' => $pageList, 'l2' => $pageView ],
    ],
    /* TENANT (ADA NESTED LIST PAGE) */

    /* MAINTENANCE FEE REPORT */
    'YuranPenyelenggaraan' => [
        'folder_path' => 'modules.MaintenanceFee.MaintenanceFeeByQuartersCategory',
        'tree_level' => '1',
        'title' => 'Yuran Penyelenggaraan Mengikut Jenis Rumah Dan Kategori Kuarters (Lokasi)',
        'title_l1' => 'Yuran Penyelenggaraan Mengikut Jenis Rumah Dan Kategori Kuarters (Lokasi)',
        'lang_file' => 'maintenance-fee',
        'route_l1' => 'maintenanceFeeByQuartersCategory.index',
        'maintenanceFeeByQuartersCategory.index'=> $pageList,
    ],

    /* DAMAGE COMPLAINT REPORT (ADA CETAK PDF) */
    'LaporanAduanKerosakan' => [
        'folder_path' => 'modules.Report.DamageComplaintReport',
        'tree_level' => '1',
        'title' => 'Laporan Aduan Kerosakan',
        'title_l1' => 'Laporan Aduan Kerosakan',
        'lang_file' => 'report-damage-complaint',
        'route_l1' => 'damageComplaintReport.index',
        'damageComplaintReport.index'=> $pageList,

    ],

    /* RULES VIOLATION COMPLAINT REPORT (ADA CETAK PDF) */
    'LaporanAduanAwam' => [
        'folder_path' => 'modules.Report.RulesViolationComplaintReport',
        'tree_level' => '1',
        'title' => 'Laporan Aduan Awam',
        'title_l1' => 'Laporan Aduan Awam',
        'lang_file' => 'report-rules-violation-complaint',
        'route_l1' => 'rulesViolationComplaintReport.index',
        'rulesViolationComplaintReport.index'=> $pageList,
    ],

    /* SPECIAL PERMISSION REPORT (ADA CETAK PDF) */
     'LaporanKebenaranKhas'
     => [
        'folder_path' => 'modules.Report.SpecialPermission',
        'tree_level' => '1',
        'title' => 'Laporan Kebenaran Khas',
        'title_l1' => 'Laporan Kebenaran Khas',
        'lang_file' => 'special-permission',
        'route_l1' => 'specialPermissionReport.index',
        'specialPermissionReport.index'=> $pageList,
    ],

    /*LAPORAN PEMANTAUAN (ADA CETAK PDF) */
    'LaporanPemantauan' => [
        'folder_path' => 'modules.Report.MonitoringReport',
        'tree_level' => '1',
        'title' => 'Laporan Pemantauan Berkala (Teknikal)',
        'title_l1' => 'Laporan Pemantauan Berkala (Teknikal)',
        'lang_file' => 'monitoring-report',
        'route_l1' => 'monitoringReport.index',
        'monitoringReport.index'=> $pageList,
    ],

    /* LAPORAN DENDA KEROSAKAN (ADA CETAK PDF) */
    'LaporanDendaKerosakan' => [
        'folder_path' => 'modules.Report.TenantPenaltyReport',
        'tree_level' => '1',
        'title' => 'Laporan Denda Kerosakan',
        'title_l1' => 'Laporan Denda Kerosakan',
        'lang_file' => 'tenant-penalty-report',
        'route_l1' => 'tenantPenaltyReport.index',
        'tenantPenaltyReport.index'=> $pageList,
    ],

    /* LAPORAN TEMUJANJI ADUAN KEROSAKAN (ADA CETAK PDF) */
    'LaporanTemujanjiAduan' => [
        'folder_path' => 'modules.Report.ComplaintAppointmentReport',
        'tree_level' => '1',
        'title' => 'Laporan Temujanji Aduan',
        'title_l1' => 'Laporan Temujanji Aduan',
        'lang_file' => 'complaint-appointment-report',
        'route_l1' => 'complaintAppointmentReport.index',
        'complaintAppointmentReport.index'=> $pageList,
    ],

    /* LAPORAN PENYELENGGARAAN (ADA CETAK PDF) */
    'LaporanPenyelenggaraan' => [
        'folder_path' => 'modules.Report.MaintenanceReport',
        'tree_level' => '1',
        'title' => 'Laporan Penyelenggaraan',
        'title_l1' => 'Laporan Penyelenggaraan',
        'lang_file' => 'maintenance-report',
        'route_l1' => 'maintenanceReport.index',
        'maintenanceReport.index'=> $pageList,
    ],

     /* LAPORAN PEMANTAUAN BERKALA (ADA CETAK PDF) */
     'LaporanPemantauanBerkala' => [
        'folder_path' => 'modules.Report.RoutineInspectionReport',
        'tree_level' => '1',
        'title' => 'Laporan Pemantauan Berkala (Pengurusan)',
        'title_l1' => 'Laporan Pemantauan Berkala (Pengurusan)',
        'lang_file' => 'maintenance-report',
        'route_l1' => 'routineInspectionReport.index',
        'routineInspectionReport.index'=> $pageList,
    ],

     /* LAPORAN DENDA HILANG KELAYAKAN KAWASAN (ADA CETAK PDF) */
     'LaporanDendaHilangKelayakan' => [
        'folder_path' => 'modules.Report.BlacklistPenaltyReport',
        'tree_level' => '1',
        'title' => 'Laporan Denda Hilang Kelayakan (Kawasan)',
        'title_l1' => 'Laporan Denda Hilang Kelayakan (Kawasan)',
        'lang_file' => 'blacklist-penalty-report',
        'route_l1' => 'blacklistPenaltyReport.index',
        'blacklistPenaltyReport.index'=> $pageList,
    ],

    /* LAPORAN DENDA HILANG KELAYAKAN  INDIVIDU (ADA CETAK PDF) */
    'LaporanDendaHilangKelayakanIndividu' => [
    'folder_path' => 'modules.Report.IndividualBlacklistPenaltyReport',
    'tree_level' => '1',
    'title' => 'Laporan Denda Hilang Kelayakan (Individu)',
    'title_l1' => 'Laporan Denda Hilang Kelayakan (Individu)',
    'lang_file' => 'individual-blacklist-penalty-report',
    'route_l1' => 'individualBlacklistPenaltyReport.index',
    'individualBlacklistPenaltyReport.index'=> $pageList,
],

    /* RULES VIOLATION COMPLAINT ANALYSIS */
    'AnalisisAduanAwam' => [
        'folder_path' => 'modules.Analysis.RulesViolationComplaintAnalysis',
        'tree_level' => '1',
        'title' => 'Analisis Aduan Awam',
        'title_l1' => 'Analisis Aduan Awam',
        'lang_file' => 'analysis-rules-violation-complaint',
        'route_l1' => 'rulesViolationComplaintAnalysis.index',
        'rulesViolationComplaintAnalysis.index'=> $pageList,
    ],

    /* DAMAGE COMPLAINT ANALYSIS */
    'AnalisisAduanKerosakan' => [
        'folder_path' => 'modules.Analysis.DamageComplaintAnalysis',
        'tree_level' => '1',
        'title' => 'Analisis Aduan Kerosakan',
        'title_l1' => 'Analisis Aduan Kerosakan',
        'lang_file' => 'analysis-damage-complaint',
        'route_l1' => 'damageComplaintAnalysis.index',
        'damageComplaintAnalysis.index'=> $pageList,
    ],

    /* APPLICATION ANALYSIS */
    'AnalisisPermohonanKuarters' => [
        'folder_path' => 'modules.Analysis.QuartersApplicationAnalysis',
        'tree_level' => '1',
        'title' => 'Analisis Permohonan Kuarters',
        'title_l1' => 'Analisis Permohonan Kuarters',
        'lang_file' => 'analysis-quarters-application',
        'route_l1' => 'quartersApplicationAnalysis.index',
        'quartersApplicationAnalysis.index'=> $pageList,
    ],

    /* QUARTERS INFO ANALYSIS */
    'AnalisisMaklumatKuarters' => [
        'folder_path' => 'modules.Analysis.QuartersInfoAnalysis',
        'tree_level' => '1',
        'title' => 'Analisis Maklumat Kuarters',
        'title_l1' => 'Analisis Maklumat Kuarters',
        'lang_file' => 'analysis-quarters-info',
        'route_l1' => 'quartersInfoAnalysis.index',
        'quartersInfoAnalysis.index'=> $pageList,
    ],

    /* MAINTENANCE - MAINTENANCE TRANSACTION */
    'TransaksiPenyelenggaraan' => [
        'folder_path' => 'modules.Maintenance.MaintenanceTransaction',
        'tree_level' => '1',
        'title' => 'Transaksi Penyelenggaraan',
        'title_l1' => 'Transaksi Penyelenggaraan',
        'lang_file' => 'maintenance-transaction',
        'route_l1' => 'maintenanceTransaction.index',
        'maintenanceTransaction.index'=> $pageList,
        'maintenanceTransaction.edit' => $pageEdit,
        'maintenanceTransaction.view' => $pageView,
    ],

    /* FINANCE - FINANCE OFFICER */
    'UnitKewangan' => [
        'folder_path' => 'modules.Finance.FinanceOfficer',
        'tree_level' => '1',
        'title' => 'Unit Kewangan',
        'title_l1' => 'Unit Kewangan',
        'lang_file' => 'finance-officer',
        'route_l1' => 'financeOfficer.index',
        'financeOfficer.index'=> $pageList,
        'financeOfficer.create' => $pageNew,
        'financeOfficer.edit' => $pageEdit,
        'financeOfficer.view' => $pageView,
    ],

    /* FINANCE - BANK ACCOUNT */
    'AkaunBank' => [
        'folder_path' => 'modules.Finance.BankAccount',
        'tree_level' => '1',
        'title' => 'Akaun Bank',
        'title_l1' => 'Akaun Bank',
        'lang_file' => 'bank-account',
        'route_l1' => 'bankAccount.index',
        'bankAccount.index'=> $pageList,
        'bankAccount.create' => $pageNew,
        'bankAccount.edit' => $pageEdit,
        'bankAccount.view' => $pageView,
    ],

     /* FINANCE - PAYMENT METHOD */
     'KaedahBayaran' => [
        'folder_path' => 'modules.Finance.PaymentMethod',
        'tree_level' => '1',
        'title' => 'Kaedah Bayaran',
        'title_l1' => 'Kaedah Bayaran',
        'lang_file' => 'payment-method',
        'route_l1' => 'paymentMethod.index',
        'paymentMethod.index'=> $pageList,
        'paymentMethod.create' => $pageNew,
        'paymentMethod.edit' => $pageEdit,
        'paymentMethod.view' => $pageView,
    ],

     /* FINANCE - INCOME ACCOUNT CODE */
    'MaklumatVotHasil' => [
        'folder_path' => 'modules.Finance.IncomeAccountCode',
        'tree_level' => '1',
        'title' => 'Maklumat Vot Hasil',
        'title_l1' => 'Maklumat Vot Hasil',
        'lang_file' => 'income-account-code',
        'route_l1' => 'incomeAccountCode.index',
        'incomeAccountCode.index'=> $pageList,
        'incomeAccountCode.create' => $pageNew,
        'incomeAccountCode.edit' => $pageEdit,
        'incomeAccountCode.view' => $pageView,
    ],

       /* FINANCE - PENALTY   (ADA NESTED LIST PAGE) */
    'Denda' => [
        'folder_path' => 'modules.Penalty.Penalty',
        'tree_level' => '2',
        'title' => 'Denda',
        'title_l1' => 'Kategori Kuarters (Lokasi)',
        'title_l2' => 'Denda',
        'lang_file' => 'penalty',
        'route_l1' => 'penalty.index',
        'route_l2' => 'penalty.penaltyList',

        'penalty.index' => [ 'l1' => $pageList ],
        'penalty.penaltyList' => [  'l1' => $pageList, 'l2' => $pageList ],
        'penalty.create' => [  'l1' => $pageList, 'l2' => $pageNew ],
        'penalty.view' => [  'l1' => $pageList, 'l2' => $pageView ],
        'penalty.edit' => [  'l1' => $pageList, 'l2' => $pageEdit ],

    ],

    /* FINANCE - PAYMENT NOTICE SCHEDULE */
    'JadualNotisBayaran' => [
        'folder_path' => 'modules.Finance.PaymentNoticeSchedule',
        'tree_level' => '1',
        'title' => 'Jadual Notis Bayaran',
        'title_l1' => 'Jadual Notis Bayaran',
        'lang_file' => 'payment-notice-schedule',
        'route_l1' => 'paymentNoticeSchedule.listYear',
        'paymentNoticeSchedule.listYear' => [ 'l1' => $pageList ],
        'paymentNoticeSchedule.index' => [ 'l1' => $pageList, 'l2' => $pageList ],
        'paymentNoticeSchedule.create' => [ 'l1' => $pageList, 'l2' => $pageNew ],
        'paymentNoticeSchedule.edit' => [ 'l1' => $pageList, 'l2' => $pageEdit ],
        'paymentNoticeSchedule.view' => [ 'l1' => $pageList, 'l2' => $pageView ],
    ],

    /* FINANCE - PAYMENT NOTICE TRANSACTION */
    'TransaksiNotisBayaran' => [
        'folder_path' => 'modules.Finance.PaymentNoticeTransaction',
        'tree_level' => '3',
        'title' => 'Transaksi Notis Bayaran',
        'title_l1' => 'Tahun',
        'title_l2' => 'Bulan',
       // 'title_l3' => 'Kategori Kuarters (Lokasi) - Berpenghuni',
        'title_l3' => 'Penghuni',
        'lang_file' => 'payment-notice-transaction',
        'route_l1' => 'paymentNoticeTransaction.listYear',
        'route_l2' => 'paymentNoticeTransaction.listPaymentNoticeSchedule',
        //'route_l3' => 'paymentNoticeTransaction.listQuartersCategoryWithTenant',
        'route_l3' => 'paymentNoticeTransaction.listTenant',
        'paymentNoticeTransaction.listYear' => [ 'l1' => $pageList ],
        'paymentNoticeTransaction.listPaymentNoticeSchedule' => [ 'l1' => $pageList, 'l2' => $pageList, 'data' => [ 'year' => $year] ],
        //'paymentNoticeTransaction.listQuartersCategoryWithTenant' => [ 'l1' => $pageList, 'l2' => $pageList, 'l3' => $pageList, 'data' => [ 'year' => $year, 'month' => $month ] ],
        'paymentNoticeTransaction.listTenant' => [ 'l1' => $pageList, 'l2' => $pageList, 'l3' => $pageList, 'data' => [ 'year' => $year, 'month' => $month ] ],
    ],

    /* FINANCE - AGENCY PAYMENT NOTICE */
    'NotisBayaranAgensi' => [
        'folder_path' => 'modules.Finance.AgencyPaymentNotice',
        'tree_level' => '4',
        'title' => 'Notis Bayaran Agensi',
        'title_l1' => 'Tahun',
        'title_l2' => 'Bulan',
        'title_l3' => 'Agensi',
        'title_l4' => 'Penghuni',
        'lang_file' => 'agency-payment-notice',
        'route_l1' => 'agencyPaymentNotice.listYear',
        'route_l2' => 'agencyPaymentNotice.listPaymentNotice',
        'route_l3' => 'agencyPaymentNotice.listAgencyWithTenant',
        'route_l4' => 'agencyPaymentNotice.listTenant',
        'agencyPaymentNotice.listYear' => [ 'l1' => $pageList ],
        'agencyPaymentNotice.listPaymentNotice' => [ 'l1' => $pageList, 'l2' => $pageList, 'data' => [ 'year' => $year] ],
        'agencyPaymentNotice.listAgencyWithTenant' => [ 'l1' => $pageList, 'l2' => $pageList, 'l3' => $pageList, 'data' => [ 'year' => $year, 'month' => $month ] ],
        'agencyPaymentNotice.listTenant' => [ 'l1' => $pageList, 'l2' => $pageList, 'l3' => $pageList, 'l4' => $pageList ],
    ],

      /* FINANCE - PAYMENT RECORD */
      'RekodBayaran' => [
        'folder_path' => 'modules.Finance.PaymentRecord',
        'tree_level' => '4',
        'title' => 'Rekod Bayaran',
        'title_l1' => 'Rekod Bayaran',
        'lang_file' => 'payment-record',
        'route_l1' => 'paymentRecord.index',
        'paymentRecord.index'=> $pageList
    ],

    /* FINANCE - ACCOUNT RECONCILIATION - ISPEKS */
    'PenyesuaianAkauniSPEKS' => [
        'folder_path' => 'modules.Finance.AccountReconciliationIspeks',
        'tree_level' => '2',
        'title' => 'Penyesuaian Akaun iSPEKS',
        'title_l1' => 'Tahun/Bulan Notis Bayaran',
        'title_l2' => 'Transaksi Penyesuaian Akaun iSPEKS',
        'lang_file' => 'account-reconciliation-ispeks',
        'route_l1' => 'accountReconciliationIspeks.listYearMonth',
        'route_l2' => 'accountReconciliationIspeks.listTransaction',
        'accountReconciliationIspeks.listYearMonth'=> $pageList,
        'accountReconciliationIspeks.listTransaction'=> [ 'l1' => $pageList, 'l2' => $pageList ],
        'accountReconciliationIspeks.create' => [ 'l1' => $pageList, 'l2' => $pageNew ],
        'accountReconciliationIspeks.edit' => [ 'l1' => $pageList, 'l2' => $pageEdit ],
        'accountReconciliationIspeks.view' => [ 'l1' => $pageList, 'l2' => $pageView ],
    ],
    /* FINANCE - ACCOUNT RECONCILIATION - IGFMAS */
    'PenyesuaianAkauniGFMAS' => [
        'folder_path' => 'modules.Finance.AccountReconciliationIgfmas',
        'tree_level' => '2',
        'title' => 'Penyesuaian Akaun iGFMAS',
        'title_l1' => 'Tahun/Bulan Notis Bayaran',
        'title_l2' => 'Transaksi Penyesuaian Akaun iGFMAS',
        'lang_file' => 'account-reconciliation-igfmas',
        'route_l1' => 'accountReconciliationIgfmas.listYearMonth',
        'route_l2' => 'accountReconciliationIgfmas.listTransaction',
        'accountReconciliationIgfmas.listYearMonth'=> $pageList,
        'accountReconciliationIgfmas.listTransaction'=> [ 'l1' => $pageList, 'l2' => $pageList ],
        'accountReconciliationIgfmas.create' => [ 'l1' => $pageList, 'l2' => $pageNew ],
        'accountReconciliationIgfmas.edit' => [ 'l1' => $pageList, 'l2' => $pageEdit ],
        'accountReconciliationIgfmas.view' => [ 'l1' => $pageList, 'l2' => $pageView ],
    ],
     /* FINANCE - COLLECTOR STATEMENT */
    'PenyataPemungut' => [
        'folder_path' => 'modules.Finance.CollectorStatement',
        'tree_level' => '1',
        'title' => 'Penyata Pemungut',
        'title_l1' => 'Penyata Pemungut',
        'lang_file' => 'collector-statement',
        'route_l1' => 'collectorStatement.index',
        'collectorStatement.index'=> $pageList,
        'collectorStatement.create' => $pageNew,
        'collectorStatement.edit' => $pageEdit,
        'collectorStatement.view' => $pageView,
    ],

    /* FINANCE - JOURNAL ADJUSTMENT*/
    'JurnalPelarasan' => [
        'folder_path' => 'modules.Finance.JournalAdjustment',
        'tree_level' => '1',
        'title' => 'Jurnal Pelarasan',
        'title_l1' => 'Jurnal Pelarasan',
        'lang_file' => 'journal-adjustment',
        'route_l1' => 'journalAdjustment.index',
        'journalAdjustment.index'=> $pageList,
        'journalAdjustment.create' => $pageNew,
        'journalAdjustment.edit' => $pageEdit,
        'journalAdjustment.view' => $pageView,
    ],

    /* FINANCE - INTERNAL JOURNAL ADJUSTMENT*/
    'JurnalPelarasanDalaman' => [
        'folder_path' => 'modules.Finance.InternalJournalAdjustment',
        'tree_level' => '1',
        'title' => 'Jurnal Pelarasan Dalaman',
        'title_l1' => 'Jurnal Pelarasan Dalaman',
        'lang_file' => 'internal-journal-adjustment',
        'route_l1' => 'internalJournalAdjustment.index',
        'internalJournalAdjustment.index'=> $pageList,
        'internalJournalAdjustment.create' => $pageNew,
        'internalJournalAdjustment.edit' => $pageEdit,
        'internalJournalAdjustment.view' => $pageView,
    ],

    /* FINANCE - ISPEKS INTEGRATION - INCOMING*/
    'IntegrasiIspeksIncoming' => [
        'folder_path' => 'modules.Finance.IspeksIntegration',
        'tree_level' => '1',
        'title' => 'Proses Integrasi ke Ispeks',
        'title_l1' => 'Proses Integrasi ke Ispeks',
        'lang_file' => 'ispeks-integration',
        'route_l1' => 'ispeksIntegrationIncoming.index',
        'ispeksIntegrationIncoming.index'=> "",
    ],

    /* FINANCE - ISPEKS INTEGRATION - OUTGOING*/
    'IntegrasiIspeksOutgoing' => [
        'folder_path' => 'modules.Finance.IspeksIntegration',
        'tree_level' => '1',
        'title' => 'Proses Integrasi dari Ispeks',
        'title_l1' => 'Proses Integrasi dari Ispeks',
        'lang_file' => 'ispeks-integration',
        'route_l1' => 'ispeksIntegrationOutgoing.index',
        'ispeksIntegrationOutgoing.index'=> "",
    ],

    /* FINANCE - ISPEKS INTEGRATION*/
    'IntegrasiIspeks' => [
        'folder_path' => 'modules.Finance.IspeksIntegration',
        'tree_level' => '1',
        'title' => 'Integrasi Ispeks',
        'title_l1' => 'Integrasi Ispeks',
        'lang_file' => 'ispeks-integration',
        'route_l1' => 'ispeksIntegration.index',
        'ispeksIntegration.index'=> "",
    ],

    /* PENALTY - BLACKLIST PENALTY RATE */
    'KadarDendaHilangKelayakan' => [
        'folder_path' => 'modules.Penalty.BlacklistPenaltyRate',
        'tree_level' => '1',
        'title' => 'Kadar Denda Hilang Kelayakan',
        'title_l1' => 'Kadar Denda Hilang Kelayakan',
        'lang_file' => 'blacklist-penalty-rate',
        'route_l1' => 'blacklistPenaltyRate.index',
        'blacklistPenaltyRate.index'=> $pageList,
        'blacklistPenaltyRate.create'=> $pageNew,
        'blacklistPenaltyRate.edit'=> $pageEdit,
        'blacklistPenaltyRate.view'=> $pageView,
    ],

    /* PENALTY - BLACKLIST PENALTY */
    'DendaHilangKelayakan' => [
        'folder_path' => 'modules.Penalty.BlacklistPenalty',
        'tree_level' => '1',
        'title' => 'Denda Hilang Kelayakan',
        'title_l1' => 'Kategori Kuarters (Lokasi)',
        'title_l2' => 'Denda Hilang Kelayakan',
        'lang_file' => 'blacklist-penalty',
        'route_l1' => 'blacklistPenalty.index',
        'blacklistPenalty.index'=> $pageList,
        'blacklistPenalty.penaltyList'=> $pageList,
        'blacklistPenalty.create'=> $pageNew,
        'blacklistPenalty.edit'=> $pageEdit,
        'blacklistPenalty.view'=> $pageView,
    ],

    /* DYNAMIC REPORTING - REPORT*/
    'LaporanDinamik' => [
        'folder_path' => 'modules.DynamicReport.DynamicReport',
        'tree_level' => '1',
        'title' => 'Laporan Dinamik',
        'title_l1' => 'Laporan Dinamik',
        'lang_file' => 'dynamic-report',
        'route_l1' => 'dynamicReport.index',
        'dynamicReport.index'=> $pageList
    ],

    /* FINANCE REPORT - COLLECTOR STATEMENT REPORT */
    'LaporanPenyataPemungut' => [
        'folder_path' => 'modules.FinanceReport.CollectorStatementReport',
        'tree_level' => '1',
        'title' => 'Laporan Penyata Pemungut',
        'title_l1' => 'Laporan Penyata Pemungut',
        'lang_file' => 'collector-statement-report',
        'route_l1' => 'collectorStatementReport.index',
        'collectorStatementReport.index'=> $pageList
    ],

    /* FINANCE REPORT - SALES REPORT*/
    'LaporanTerimaanHasil' => [
        'folder_path' => 'modules.FinanceReport.SalesReport',
        'tree_level' => '1',
        'title' => 'Laporan Terimaan Hasil',
        'title_l1' => 'Laporan Terimaan Hasil',
        'lang_file' => 'sales-report',
        'route_l1' => 'salesReport.index',
        'salesReport.index'=> $pageList
    ],

    /* FINANCE REPORT - JOURNAL ADJUSTMENT REPORT*/
    'LaporanJurnalPelarasan' => [
        'folder_path' => 'modules.FinanceReport.JournalReport',
        'tree_level' => '1',
        'title' => 'Laporan Jurnal Pelarasan',
        'title_l1' => 'Laporan Jurnal Pelarasan',
        'lang_file' => 'journal-report',
        'route_l1' => 'journalReport.index',
        'journalReport.index'=> $pageList
    ],

    /* FINANCE REPORT - SALES SUMMARY REPORT*/
    'LaporanRingkasanTerimaanHasil' => [
        'folder_path' => 'modules.FinanceReport.SalesSummaryReport',
        'tree_level' => '1',
        'title' => 'Laporan Ringkasan Terimaan Hasil',
        'title_l1' => 'Laporan Ringkasan Terimaan Hasil',
        'lang_file' => 'sales-summary-report',
        'route_l1' => 'salesSummaryReport.index',
        'salesSummaryReport.index'=> $pageList
    ],

    /* FINANCE REPORT - INTERNAL JOURNAL ADJUSTMENT REPORT*/
    'LaporanJurnalPelarasanDalaman' => [
        'folder_path' => 'modules.FinanceReport.InternalJournalReport',
        'tree_level' => '1',
        'title' => 'Laporan Jurnal Pelarasan Dalaman',
        'title_l1' => 'Laporan Jurnal Pelarasan Dalaman',
        'lang_file' => 'internal-journal-report',
        'route_l1' => 'internalJournalReport.index',
        'internalJournalReport.index'=> $pageList
    ],

    /* FINANCE REPORT - SALES ESTIMATION REPORT*/
    'LaporanAnggaranTerimaanHasil' => [
        'folder_path' => 'modules.FinanceReport.SalesEstimationReport',
        'tree_level' => '1',
        'title' => 'Laporan Anggaran Terimaan Hasil',
        'title_l1' => 'Laporan Anggaran Terimaan Hasil',
        'lang_file' => 'sales-estimation-report',
        'route_l1' => 'salesEstimationReport.index',
        'salesEstimationReport.index'=> $pageList
    ],

    /* FINANCE REPORT - SALES PERFORMANCE REPORT*/
    'LaporanPrestasiTerimaanHasil' => [
        'folder_path' => 'modules.FinanceReport.SalesPerformanceReport',
        'tree_level' => '1',
        'title' => 'Laporan Prestasi Terimaan Hasil',
        'title_l1' => 'Laporan Prestasi Terimaan Hasil',
        'lang_file' => 'sales-performance-report',
        'route_l1' => 'salesPerformanceReport.index',
        'salesPerformanceReport.index'=> $pageList
    ],


    /* FINANCE REPORT - MAINTENANCE FEE COMPARISON REPORT*/
    'PerbandinganYuranPenyelenggaraan' => [
        'folder_path' => 'modules.FinanceReport.MaintenanceFeeComparisonReport',
        'tree_level' => '1',
        'title' => 'Laporan Perbandingan Yuran Penyelenggaraan',
        'title_l1' => 'Laporan Perbandingan Yuran Penyelenggaraan',
        'lang_file' => 'maintenance-fee-comparison-report',
        'route_l1' => 'maintenanceFeeComparisonReport.index',
        'maintenanceFeeComparisonReport.index'=> $pageList
    ],

    /* FINANCE REPORT - INDIVIDUAL STATEMENT REPORT*/
    'LaporanPenyataIndividu' => [
        'folder_path' => 'modules.FinanceReport.IndividualStatementReport',
        'tree_level' => '1',
        'title' => 'Laporan Penyata Individu',
        'title_l1' => 'Laporan Penyata Individu',
        'lang_file' => 'individual-statement-report',
        'route_l1' => 'individualStatementReport.index',
        'individualStatementReport.index'=> ""
    ],

    /* FINANCE REPORT - CASH BOOK*/
    'LaporanBukuTunai' => [
        'folder_path' => 'modules.FinanceReport.CashBookReport',
        'tree_level' => '1',
        'title' => 'Laporan Buku Tunai',
        'title_l1' => 'Laporan Buku Tunai',
        'lang_file' => 'cash-book',
        'route_l1' => 'cashBookReport.index',
        'cashBookReport.index'=> $pageList
    ],

    'LaporanNotisBayaran' => [
        'folder_path' => 'modules.FinanceReport.NoticePaymentReport',
        'tree_level' => '1',
        'title' => 'Laporan Notis Bayaran',
        'title_l1' => 'Laporan Notis Bayaran',
        'lang_file' => 'notice-payment-report',
        'route_l1' => 'noticePaymentReport.index',
        'noticePaymentReport.index'=> $pageList
    ],

     /* AUDIT TRAIL */
     'KawalanAudit' => [
        'folder_path' => 'modules.AuditTrail.AuditTrail',
        'tree_level' => '1',
        'title' => 'Kawalan Audit',
        'title_l1' => 'Kawalan Audit',
        'lang_file' => 'audit-trail',
        'route_l1' => 'auditTrail.index',
        'auditTrail.index'=> $pageList,
        'auditTrail.view'=> $pageView,

    ],


];
