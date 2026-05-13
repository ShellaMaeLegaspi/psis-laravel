<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\PPMPController;
use App\Http\Controllers\PPAPController;
use App\Http\Controllers\SecurityController;
use App\Http\Controllers\EmployeesController;
use App\Http\Controllers\ItemsController;
use App\Http\Controllers\IARController;
use App\Http\Controllers\POController;
use App\Http\Controllers\PARController;
use App\Http\Controllers\RISController;
use App\Http\Controllers\RFQController;
use App\Http\Controllers\NOAController;
use App\Http\Controllers\NTPController;
use App\Http\Controllers\BIDController;
use App\Http\Controllers\ICSController;
use App\Http\Controllers\SPBIController;
use App\Http\Controllers\PTRController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PropertyController;
use App\Http\Controllers\ReportsController;
use App\Http\Controllers\ParametersController;
use App\Http\Controllers\BranchController;
use App\Http\Controllers\FMISController;
use App\Http\Middleware\RequireSessionLogin;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Maps to CI routes:
|   login/form     -> GET /login
|   login/get_user -> GET /login/get-user (AJAX)
|   ppmp           -> GET /ppmp (placeholder dashboard)
|
*/

// Redirect root to login
Route::get('/', function () {
    return redirect('/login');
})->name('home');

Route::get('/login', [LoginController::class, 'form'])->name('login');

// PPAP routes
Route::get('/ppap', [PPAPController::class, 'index'])->name('ppap.index');
Route::get('/ppap/preparation_inbox', [PPAPController::class, 'preparationInbox'])->name('ppap.preparation_inbox');
Route::get('/ppap/approval_inbox', [PPAPController::class, 'approvalInbox'])->name('ppap.approval_inbox');
Route::get('/ppap/certification_inbox', [PPAPController::class, 'certificationInbox'])->name('ppap.certification_inbox');
Route::get('/ppap/receiving_inbox', [PPAPController::class, 'receivingInbox'])->name('ppap.receiving_inbox');
Route::get('/ppap/query', [PPAPController::class, 'query'])->name('ppap.query');
Route::get('/ppap/preparation', [PPAPController::class, 'preparation'])->name('ppap.preparation');

// Test database connection
Route::get('/test-db', function () {
    try {
        \DB::connection('sqlsrv')->getPdo();
        return "SQL Server connection successful! Database: " . \DB::connection('sqlsrv')->getDatabaseName();
    } catch (\Exception $e) {
        return "SQL Server connection failed: " . $e->getMessage();
    }
});

// Login routes
Route::get('/login', [LoginController::class, 'form'])->name('login');
Route::get('/login/get-user', [LoginController::class, 'getUser'])->name('login.getUser');

Route::middleware([RequireSessionLogin::class])->group(function () {
    // PPMP routes
    Route::get('/ppmp', [PPMPController::class, 'index'])->name('ppmp');
    Route::get('/ppmp/preparation_inbox', [PPMPController::class, 'preparationInbox'])->name('ppmp.preparation_inbox');
    Route::get('/ppmp/evaluation_inbox', [PPMPController::class, 'evaluationInbox'])->name('ppmp.evaluation_inbox');
    Route::get('/ppmp/approval_inbox', [PPMPController::class, 'approvalInbox'])->name('ppmp.approval_inbox');
    Route::get('/ppmp/certification_inbox', [PPMPController::class, 'certificationInbox'])->name('ppmp.certification_inbox');
    Route::get('/ppmp/receiving_inbox', [PPMPController::class, 'receivingInbox'])->name('ppmp.receiving_inbox');
    Route::get('/ppmp/query', [PPMPController::class, 'query'])->name('ppmp.query');
    Route::get('/ppmp/preparation/{PreparatoryFormat?}', [PPMPController::class, 'preparation'])->name('ppmp.preparation');
    Route::post('/ppmp/query_ppmp', [PPMPController::class, 'queryPPMP'])->name('ppmp.query_ppmp');
    Route::post('/ppmp/ajx_get_headers', [PPMPController::class, 'ajxGetHeaders'])->name('ppmp.ajx_get_headers');
    Route::post('/ppmp/save_initial_header', [PPMPController::class, 'saveInitialHeader'])->name('ppmp.save_initial_header');
    Route::post('/ppmp/get_updated_ppmp_items', [PPMPController::class, 'getUpdatedPPMPItems'])->name('ppmp.get_updated_ppmp_items');
    Route::post('/ppmp/get_ppmp_item', [PPMPController::class, 'getPpmpItem'])->name('ppmp.get_ppmp_item');
    Route::post('/ppmp/save_change_item_details', [PPMPController::class, 'saveChangeItemDetails'])->name('ppmp.save_change_item_details');
    Route::get('/ppmp/export_ppmp/{controlno}/{updated?}', [PPMPController::class, 'exportPpmp'])->name('ppmp.export_ppmp');
    Route::get('/ppmp/utilization', [PPMPController::class, 'utilization'])->name('ppmp.utilization');
    Route::get('/ppmp/utilization_per_object', [PPMPController::class, 'utilizationPerObject'])->name('ppmp.utilization_per_object');

    // IAR routes
    Route::get('/iar/preparation_inbox', [IARController::class, 'preparation_inbox'])->name('iar.preparation_inbox');
    Route::get('/iar/approval_inbox', [IARController::class, 'approval_inbox'])->name('iar.approval_inbox');
    Route::get('/iar/acceptance_inbox', [IARController::class, 'acceptance_inbox'])->name('iar.acceptance_inbox');
    Route::get('/iar/receiving_inbox', [IARController::class, 'receiving_inbox'])->name('iar.receiving_inbox');
    Route::get('/iar/query', [IARController::class, 'query'])->name('iar.query');
    Route::get('/iar/preparation', [IARController::class, 'preparation'])->name('iar.preparation');
    Route::post('/iar/get_headers', [IARController::class, 'get_headers'])->name('iar.get_headers');
    Route::get('/iar/get', [IARController::class, 'get'])->name('iar.get');

    // PO routes
    Route::get('/po/preparation_inbox', [POController::class, 'preparation_inbox'])->name('po.preparation_inbox');
    Route::get('/po/approval_inbox', [POController::class, 'approval_inbox'])->name('po.approval_inbox');
    Route::get('/po/certification_inbox', [POController::class, 'certification_inbox'])->name('po.certification_inbox');
    Route::get('/po/receiving_inbox', [POController::class, 'receiving_inbox'])->name('po.receiving_inbox');
    Route::get('/po/query', [POController::class, 'query'])->name('po.query');
    Route::get('/po/preparation', [POController::class, 'preparation'])->name('po.preparation');
    Route::post('/po/get_headers', [POController::class, 'get_headers'])->name('po.get_headers');
    Route::get('/po/get', [POController::class, 'get'])->name('po.get');
    Route::get('/po/count_preparation_inbox', [POController::class, 'count_preparation_inbox'])->name('po.count_preparation_inbox');
    Route::get('/po/count_approval_inbox', [POController::class, 'count_approval_inbox'])->name('po.count_approval_inbox');
    Route::get('/po/count_certification_inbox', [POController::class, 'count_certification_inbox'])->name('po.count_certification_inbox');
    Route::get('/po/count_receiving_inbox', [POController::class, 'count_receiving_inbox'])->name('po.count_receiving_inbox');

    // PAR routes
    Route::get('/par/preparation_inbox', [PARController::class, 'preparation_inbox'])->name('par.preparation_inbox');
    Route::get('/par/acceptance_inbox', [PARController::class, 'acceptance_inbox'])->name('par.acceptance_inbox');
    Route::get('/par/approval_inbox', [PARController::class, 'approval_inbox'])->name('par.approval_inbox');
    Route::get('/par/receiving_inbox', [PARController::class, 'receiving_inbox'])->name('par.receiving_inbox');
    Route::get('/par/query', [PARController::class, 'query'])->name('par.query');
    Route::get('/par/preparation', [PARController::class, 'preparation'])->name('par.preparation');
    Route::post('/par/get_headers', [PARController::class, 'get_headers'])->name('par.get_headers');
    Route::get('/par/get', [PARController::class, 'get'])->name('par.get');

    // RIS routes
    Route::get('/ris/preparation_inbox', [RISController::class, 'preparation_inbox'])->name('ris.preparation_inbox');
    Route::get('/ris/approval_inbox', [RISController::class, 'approval_inbox'])->name('ris.approval_inbox');
    Route::get('/ris/query', [RISController::class, 'query'])->name('ris.query');
    Route::get('/ris/preparation', [RISController::class, 'preparation'])->name('ris.preparation');
    Route::post('/ris/get_headers', [RISController::class, 'get_headers'])->name('ris.get_headers');
    Route::get('/ris/get', [RISController::class, 'get'])->name('ris.get');

    // RFQ routes
    Route::get('/rfq/preparation_inbox', [RFQController::class, 'preparationInbox'])->name('rfq.preparation_inbox');
    Route::get('/rfq/approval_inbox', [RFQController::class, 'approvalInbox'])->name('rfq.approval_inbox');
    Route::get('/rfq/query', [RFQController::class, 'query'])->name('rfq.query');
    Route::get('/rfq/preparation', [RFQController::class, 'preparation'])->name('rfq.preparation');
    Route::post('/rfq/get_headers', [RFQController::class, 'getHeaders'])->name('rfq.get_headers');
    Route::post('/rfq/get_details', [RFQController::class, 'getDetails'])->name('rfq.get_details');
    Route::post('/rfq/save', [RFQController::class, 'save'])->name('rfq.save');
    Route::post('/rfq/approve', [RFQController::class, 'approve'])->name('rfq.approve');

    // NOA routes
    Route::get('/noa/preparation_inbox', [NOAController::class, 'preparation_inbox'])->name('noa.preparation_inbox');
    Route::get('/noa/approval_inbox', [NOAController::class, 'approval_inbox'])->name('noa.approval_inbox');
    Route::get('/noa/query', [NOAController::class, 'query'])->name('noa.query');
    Route::get('/noa/preparation', [NOAController::class, 'preparation'])->name('noa.preparation');
    Route::post('/noa/get_headers', [NOAController::class, 'get_headers'])->name('noa.get_headers');
    Route::get('/noa/get', [NOAController::class, 'get'])->name('noa.get');

    // NTP routes
    Route::get('/ntp/preparation_inbox', [NTPController::class, 'preparation_inbox'])->name('ntp.preparation_inbox');
    Route::get('/ntp/approval_inbox', [NTPController::class, 'approval_inbox'])->name('ntp.approval_inbox');
    Route::get('/ntp/query', [NTPController::class, 'query'])->name('ntp.query');
    Route::get('/ntp/preparation', [NTPController::class, 'preparation'])->name('ntp.preparation');
    Route::post('/ntp/get_headers', [NTPController::class, 'get_headers'])->name('ntp.get_headers');
    Route::get('/ntp/get', [NTPController::class, 'get'])->name('ntp.get');

    // BID routes
    Route::get('/bid/preparation_inbox', [BIDController::class, 'preparation_inbox'])->name('bid.preparation_inbox');
    Route::get('/bid/approval_inbox', [BIDController::class, 'approval_inbox'])->name('bid.approval_inbox');
    Route::get('/bid/query', [BIDController::class, 'query'])->name('bid.query');
    Route::get('/bid/preparation', [BIDController::class, 'preparation'])->name('bid.preparation');
    Route::post('/bid/get_headers', [BIDController::class, 'get_headers'])->name('bid.get_headers');
    Route::get('/bid/get', [BIDController::class, 'get'])->name('bid.get');

    // ICS routes
    Route::get('/ics', [ICSController::class, 'index'])->name('ics');
    Route::get('/ics/preparation_inbox', [ICSController::class, 'preparationInbox'])->name('ics.preparation_inbox');
    Route::get('/ics/acceptance_inbox', [ICSController::class, 'acceptanceInbox'])->name('ics.acceptance_inbox');
    Route::get('/ics/approval_inbox', [ICSController::class, 'approvalInbox'])->name('ics.approval_inbox');
    Route::get('/ics/receiving_inbox', [ICSController::class, 'receivingInbox'])->name('ics.receiving_inbox');
    Route::get('/ics/query', [ICSController::class, 'query'])->name('ics.query');
    Route::get('/ics/preparation', [ICSController::class, 'preparation'])->name('ics.preparation');
    Route::post('/ics/get_headers', [ICSController::class, 'getHeaders'])->name('ics.get_headers');
    Route::post('/ics/get_details', [ICSController::class, 'getDetails'])->name('ics.get_details');
    Route::post('/ics/save', [ICSController::class, 'save'])->name('ics.save');

    // SPBI routes
    Route::get('/spbi', [SPBIController::class, 'index'])->name('spbi');
    Route::get('/spbi/preparation_inbox', [SPBIController::class, 'preparationInbox'])->name('spbi.preparation_inbox');
    Route::get('/spbi/approval_inbox', [SPBIController::class, 'approvalInbox'])->name('spbi.approval_inbox');
    Route::get('/spbi/query', [SPBIController::class, 'query'])->name('spbi.query');
    Route::get('/spbi/preparation', [SPBIController::class, 'preparation'])->name('spbi.preparation');
    Route::post('/spbi/get_headers', [SPBIController::class, 'getHeaders'])->name('spbi.get_headers');
    Route::post('/spbi/get_details', [SPBIController::class, 'getDetails'])->name('spbi.get_details');
    Route::post('/spbi/save', [SPBIController::class, 'save'])->name('spbi.save');

    // PTR routes
    Route::get('/ptr', [PTRController::class, 'index'])->name('ptr');
    Route::get('/ptr/preparation_inbox', [PTRController::class, 'preparationInbox'])->name('ptr.preparation_inbox');
    Route::get('/ptr/query', [PTRController::class, 'query'])->name('ptr.query');
    Route::get('/ptr/preparation', [PTRController::class, 'preparation'])->name('ptr.preparation');
    Route::post('/ptr/get_headers', [PTRController::class, 'getHeaders'])->name('ptr.get_headers');
    Route::post('/ptr/get_details', [PTRController::class, 'getDetails'])->name('ptr.get_details');
    Route::post('/ptr/save', [PTRController::class, 'save'])->name('ptr.save');

    Route::get('/ppmp/count_preparation_inbox', [PPMPController::class, 'countPreparationInbox'])->name('ppmp.count_preparation_inbox');
    Route::get('/ppmp/count_evaluation_inbox', [PPMPController::class, 'countEvaluationInbox'])->name('ppmp.count_evaluation_inbox');
    Route::get('/ppmp/count_approval_inbox', [PPMPController::class, 'countApprovalInbox'])->name('ppmp.count_approval_inbox');
    Route::get('/ppmp/count_certification_inbox', [PPMPController::class, 'countCertificationInbox'])->name('ppmp.count_certification_inbox');
    Route::get('/ppmp/count_receiving_inbox', [PPMPController::class, 'countReceivingInbox'])->name('ppmp.count_receiving_inbox');

    // Security routes
    Route::get('/security/user_access', [SecurityController::class, 'userAccess'])->name('security.user_access');
    Route::get('/security/user_groups', [SecurityController::class, 'userGroups'])->name('security.user_groups');
    Route::get('/security/assign_project_code', [SecurityController::class, 'assignProjectCode'])->name('security.assign_project_code');
    Route::get('/security/spbi_common_preparers', [SecurityController::class, 'spbiCommonPreparers'])->name('security.spbi_common_preparers');
    Route::get('/security/users', [SecurityController::class, 'users'])->name('security.users');
    Route::get('/security/userstousers', [SecurityController::class, 'userstousers'])->name('security.userstousers');

    // Security AJAX routes
    Route::post('/security/get_access', [SecurityController::class, 'getAccess'])->name('security.get_access');
    Route::post('/security/save_access', [SecurityController::class, 'saveAccess'])->name('security.save_access');
    Route::post('/security/get_groups', [SecurityController::class, 'getGroups'])->name('security.get_groups');
    Route::post('/security/get_group_access', [SecurityController::class, 'getGroupAccess'])->name('security.get_group_access');
    Route::post('/security/save_group', [SecurityController::class, 'saveGroup'])->name('security.save_group');
    Route::post('/security/get_spbi_preparers', [SecurityController::class, 'getSpbiPreparers'])->name('security.get_spbi_preparers');
    Route::post('/security/get_users', [SecurityController::class, 'getUsers'])->name('security.get_users');
    Route::get('/security/save_user', [SecurityController::class, 'saveUser'])->name('security.save_user');
    Route::post('/security/sec_save_users_to_users', [SecurityController::class, 'secSaveUsersToUsers'])->name('security.sec_save_users_to_users');
    Route::post('/security/sec_delete_users_to_users', [SecurityController::class, 'secDeleteUsersToUsers'])->name('security.sec_delete_users_to_users');
    Route::post('/security/ppmp_add_user_access', [SecurityController::class, 'ppmpAddUserAccess'])->name('security.ppmp_add_user_access');

    // HRIS Employees AJAX routes
    Route::post('/employees/getEmployees', [EmployeesController::class, 'getEmployees'])->name('employees.getEmployees');

    // Items AJAX routes
    Route::post('/items/search_main_articles', [ItemsController::class, 'searchMainArticles'])->name('items.search_main_articles');

    // Property routes
    Route::get('/property', [PropertyController::class, 'index'])->name('property.index');
    Route::get('/property/property_items', [PropertyController::class, 'propertyItems'])->name('property.property_items');
    Route::get('/property/prisup_preparation', [PropertyController::class, 'prisupPreparation'])->name('property.prisup_preparation');
    Route::get('/property/prisup_preparation_inbox', [PropertyController::class, 'prisupPreparationInbox'])->name('property.prisup_preparation_inbox');
    Route::get('/property/prisup_query', [PropertyController::class, 'prisupQuery'])->name('property.prisup_query');
    Route::get('/property/prisup_import', [PropertyController::class, 'prisupImport'])->name('property.prisup_import');
    Route::get('/property/pal_preparation', [PropertyController::class, 'palPreparation'])->name('property.pal_preparation');
    Route::get('/property/pal_query', [PropertyController::class, 'palQuery'])->name('property.pal_query');
    Route::get('/property/pal_import', [PropertyController::class, 'palImport'])->name('property.pal_import');
    Route::get('/property/iirup_preparation', [PropertyController::class, 'iirupPreparation'])->name('property.iirup_preparation');
    Route::get('/property/iirup_preparation_inbox', [PropertyController::class, 'iirupPreparationInbox'])->name('property.iirup_preparation_inbox');
    Route::get('/property/iirup_query', [PropertyController::class, 'iirupQuery'])->name('property.iirup_query');
    Route::get('/property/iirup_import', [PropertyController::class, 'iirupImport'])->name('property.iirup_import');

    // Property AJAX routes
    Route::post('/property/get_pal', [PropertyController::class, 'getPal'])->name('property.get_pal');
    Route::post('/property/save_pal', [PropertyController::class, 'savePal'])->name('property.save_pal');
    Route::post('/property/get_prisup_headers', [PropertyController::class, 'getPrisupHeaders'])->name('property.get_prisup_headers');
    Route::post('/property/save_prisup', [PropertyController::class, 'savePrisup'])->name('property.save_prisup');
    Route::post('/property/get_iirup_headers', [PropertyController::class, 'getIirupHeaders'])->name('property.get_iirup_headers');
    Route::post('/property/save_iirup', [PropertyController::class, 'saveIirup'])->name('property.save_iirup');

    // Reports routes
    Route::get('/reports/pr_monitoring', [ReportsController::class, 'prMonitoring'])->name('reports.pr_monitoring');
    Route::get('/reports/pr_monitoring_v2', [ReportsController::class, 'prMonitoringV2'])->name('reports.pr_monitoring_v2');
    Route::get('/reports/abstract_monitoring', [ReportsController::class, 'abstractMonitoring'])->name('reports.abstract_monitoring');
    Route::get('/reports/po_monitoring', [ReportsController::class, 'poMonitoring'])->name('reports.po_monitoring');
    Route::get('/reports/ics', [ReportsController::class, 'ics'])->name('reports.ics');
    Route::get('/reports/iar', [ReportsController::class, 'iar'])->name('reports.iar');
    Route::get('/reports/pal', [ReportsController::class, 'pal'])->name('reports.pal');
    Route::get('/reports/par', [ReportsController::class, 'par'])->name('reports.par');
    Route::get('/reports/pmr', [ReportsController::class, 'pmr'])->name('reports.pmr');
    Route::get('/reports/po', [ReportsController::class, 'po'])->name('reports.po');
    Route::get('/reports/ris', [ReportsController::class, 'ris'])->name('reports.ris');

    // Reports AJAX routes
    Route::post('/reports/get_extract_pr_monitoring', [ReportsController::class, 'getExtractPrMonitoring'])->name('reports.get_extract_pr_monitoring');
    Route::post('/reports/extract_pr_monitoring', [ReportsController::class, 'extractPrMonitoring'])->name('reports.extract_pr_monitoring');
    Route::post('/reports/get_extract_abstract_monitoring', [ReportsController::class, 'getExtractAbstractMonitoring'])->name('reports.get_extract_abstract_monitoring');
    Route::post('/reports/extract_abstract_monitoring', [ReportsController::class, 'extractAbstractMonitoring'])->name('reports.extract_abstract_monitoring');
    Route::post('/reports/get_extract_po_monitoring', [ReportsController::class, 'getExtractPoMonitoring'])->name('reports.get_extract_po_monitoring');
    Route::post('/reports/extract_po_monitoring', [ReportsController::class, 'extractPoMonitoring'])->name('reports.extract_po_monitoring');

    // Missing Controllers Routes
    Route::get('/parameters', [ParametersController::class, 'index'])->name('parameters.index');
    Route::get('/branch', [BranchController::class, 'index'])->name('branch.index');
    Route::get('/fmis', [FMISController::class, 'index'])->name('fmis.index');
});
