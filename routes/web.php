<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AffiliationController;
use App\Http\Controllers\CollaborationController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FindController;
use App\Http\Controllers\InboxController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\PaperController;
use App\Http\Controllers\PaperSettingController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get("/", function () {
    return redirect("/login");
});

Route::middleware('guest')->group(function () {
    Route::prefix("/login")->group(function(){
        Route::get('/', [LoginController::class, 'index'])->name("login");

        Route::get('/forgot-password', [LoginController::class, 'indexForgotPassword']);

        Route::post('/', [LoginController::class, 'loginUser']);

        Route::post('/reset-password', [LoginController::class, 'resetPassword']);
    });

    Route::prefix('/register')->group(function () {
        Route::get('/', [RegisterController::class,'index']);

        Route::get('/{role}', [RegisterController::class, 'indexRole'] );

        Route::post('/{role}/insert', [RegisterController::class, 'insertNewUser'] );
    });
});

Route::middleware('auth')->group(function () {
    Route::post("/logout", [LoginController::class,"logoutUser"]);

    Route::get("/find", [FindController::class,"index"]);

    Route::get('/search-user-lecturer', [UserController::class, 'searchUserLecturer'])->name('api.users.search.lecturer');

    Route::post('/report/submit', [ReportController::class, 'submitReport']);

    Route::prefix("/settings")->group(function(){
        Route::get("/", [SettingController::class,"index"]);

        Route::post("/update-public-profile", [SettingController::class,"updatePublicProfile"]);

        Route::post("/request-affiliation", [SettingController::class,"requestAffiliation"]);

        Route::post("/update-interests", [SettingController::class,"updateInterests"]);

        Route::post("/update-email", [SettingController::class,"updateEmail"]);

        Route::post("/update-password", [SettingController::class,"updatePassword"]);

        Route::post("/delete-account", [SettingController::class,"deleteAccount"]);
    });

    Route::prefix("/inboxes")->group(function(){
        Route::get("/", [InboxController::class,"index"]);

        Route::get("/drafts", [InboxController::class,"indexDrafts"]);

        Route::get("/sent", [InboxController::class,"indexSent"]);

        Route::prefix("/compose")->group(function(){
            Route::get("/", [InboxController::class,"indexCompose"]);


            Route::prefix("/{inboxId}")->group(function(){
                Route::get("/", [InboxController::class,"indexComposeInboxId"]);

                Route::post("/", [InboxController::class,"saveOrSendInbox"]);

                Route::post("/delete-draft", [InboxController::class,"deleteDraftInbox"]);
            });
        });

        Route::get("/{inboxId}", [InboxController::class,"indexSpecificInbox"]);
    }); 

    Route::prefix("/{profileId}")->group(function(){
        Route::get("/dashboard", [DashboardController::class, "index"])->name('dashboard');

        Route::get("/papers", [PaperController::class,"indexPapers"]);

        Route::get("/stars", [PaperController::class,"indexStars"]);

        Route::get("/overview", [ProfileController::class,"indexOverview"]);

        Route::get("/followers", [ProfileController::class,"indexFollowers"]);

        Route::get("/researchers", [ProfileController::class,"indexResearchers"]);

        Route::prefix("/grants")->group(function(){
            Route::get("/", [AffiliationController::class, "indexOpportunities"])->name('lecturer.grants');
            
            Route::post("/apply", [AffiliationController::class, "sendRequest"]);
        });

        Route::prefix("/university")->group(function(){
            // View Page
            Route::get("/requests", [AffiliationController::class, "indexRequests"])->name('uni.requests');
            
            // Actions
            Route::post("/request/approve", [AffiliationController::class, "approveRequest"]);
            Route::post("/request/reject", [AffiliationController::class, "rejectRequest"]);
        });

        Route::prefix("/paper/{paperId}")->group(function(){
            Route::get("/overview", [PaperController::class,"paperOverview"]);

            Route::get("/workspace", [PaperController::class,"paperWorkspace"]);
            
            Route::get("/lit-review", [PaperController::class, "LitReview"]);

            Route::post("/add-reference", [PaperController::class,"addReference"]);

            Route::post("/save-synthesis", [PaperController::class, "saveSynthesis"]);

            Route::get("/export-bibtex", [PaperController::class, "exportBibtex"]);

            Route::post("/add-theme", [PaperController::class, "addTheme"]);

            Route::post("/remove-theme", [PaperController::class, "removeTheme"]);

            Route::post("/toggle-collaboration", [PaperController::class,"toggleCollaboration"]);
            
            Route::get("/lit-review", [PaperController::class, "paperLitReview"]);
            
            Route::post("/finalize-lit-review", [PaperController::class, "finalizeLitReview"]);

            Route::get("/methodology", [PaperController::class, "paperMethodology"]);

            Route::post("/save-methodology", [PaperController::class, "saveMethodology"]);

            Route::post("/methodology/add-dataset", [PaperController::class, "addDataset"]);

            Route::post("/methodology/update-dataset", [PaperController::class, "updateDataset"]);

            Route::post("/methodology/remove-dataset", [PaperController::class, "removeDataset"]);

            Route::post("/methodology/add-formula", [PaperController::class, "addFormula"]);

            Route::post("/methodology/remove-formula", [PaperController::class, "removeFormula"]);

            Route::post("/methodology/add-code", [PaperController::class, "addCodeBlock"]);

            Route::post("/methodology/remove-code", [PaperController::class, "removeCodeBlock"]);

            Route::post("/finalize-methodology", [PaperController::class, "finalizeMethodology"]);

            Route::get("/results", [PaperController::class, "paperResults"]);

            Route::post("/results/add-chart", [PaperController::class, "addResultChart"]);

            Route::post("/results/add-table", [PaperController::class, "addResultTable"]);

            Route::post("/results/update", [PaperController::class, "updateResultItem"]);

            Route::post("/results/delete", [PaperController::class, "deleteResultItem"]);

            Route::post("/finalize-results", [PaperController::class, "finalizeResults"]);

            Route::get("/conclusion", [PaperController::class, "paperConclusion"]);

            Route::post("/save-conclusion", [PaperController::class, "saveConclusion"]);

            Route::post("/finalize-conclusion", [PaperController::class, "finalizeConclusion"]);

            Route::post("/create-new-project-role", [PaperController::class,"createNewProjectRole"]);

            Route::prefix("/settings")->group(function(){
                Route::get("/", [PaperSettingController::class,"index"]);

                Route::post("/update-paper", [PaperSettingController::class,"updatePaper"]);

                Route::post("/delete-paper", [PaperSettingController::class,"deletePaper"]);
            });

            Route::prefix("/collaborations")->group(function(){
                Route::get("/", [CollaborationController::class,"index"]);
                

                Route::post("/toggle-collaboration", [CollaborationController::class,"toggleCollaboration"]);

                Route::post("/create-new-role", [CollaborationController::class,"createNewRole"]);

                Route::post("/remove-role", [CollaborationController::class,"removeRole"]);

                Route::post('/invite', [CollaborationController::class, 'inviteUser']);

                Route::post('/cancel-invitation', [CollaborationController::class, 'cancelInvitation']);

                Route::post('/clear-invitation-history', [CollaborationController::class, 'clearInvitationHistory']);

                Route::post('/accept-invitation', [CollaborationController::class, 'acceptInvitation']);

                Route::post('/reject-invitation', [CollaborationController::class, 'rejectInvitation']);

                Route::post('/accept-request', [CollaborationController::class, 'acceptRequest']);

                Route::post('/reject-request', [CollaborationController::class, 'rejectRequest']);

                Route::post('/remove-request', [CollaborationController::class, 'removeRequest']);

                Route::post('/clear-request-history', [CollaborationController::class, 'clearRequestHistory']);

                Route::post('/edit-role', [CollaborationController::class, 'editRole']);

                Route::post('/remove-member', [CollaborationController::class, 'removeMember']);

                Route::post('/apply-for-role', [CollaborationController::class, 'applyForRole']);
            });
        });
    });

    Route::prefix("/papers")->group(function () {
        Route::get("/create", [PaperController::class,"indexCreatePaper"]);

        Route::post("/create-new-paper", [PaperController::class,"createNewPaper"]);

        Route::post('/{paperId}/star', [PaperController::class, 'toggleStar']);
    });

    Route::prefix("/admin-panel")->group(function () {
        Route::get("/", [AdminController::class,"index"]);

        Route::prefix("/master-data")->group(function () {
            Route::prefix("/research-fields")->group(function () {
                Route::get("/", [AdminController::class,"indexResearchFields"]);

                Route::post("/create", [AdminController::class,"createResearchFields"]);

                Route::post("/update", [AdminController::class,"updateResearchFields"]);

                Route::post("/delete", [AdminController::class,"deleteResearchFields"]);
            });

            Route::prefix("/paper-types")->group(function () {
                Route::get("/", [AdminController::class,"indexPaperTypes"]);

                Route::post("/create", [AdminController::class,"createPaperTypes"]);

                Route::post("/update", [AdminController::class,"updatePaperTypes"]);

                Route::post("/delete", [AdminController::class,"deletePaperTypes"]);
            });
        });

        Route::prefix("/monitoring")->group(function () {
            Route::get("/activity-logs", [AdminController::class,"indexActivityLogs"]);

            Route::get("/global-statistics", [AdminController::class,"indexGlobalStatistics"]);
        });

        Route::prefix("/request")->group(function () {
            Route::prefix("/user-report")->group(function () {
                Route::get("/", [AdminController::class,"indexUserReport"]);

                Route::post("/{reportId}", [AdminController::class,"manageUserReport"]);
            });
        });
    });
});
