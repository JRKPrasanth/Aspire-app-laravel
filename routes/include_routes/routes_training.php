<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TopicController;
use App\Http\Controllers\DepartmenttopicController;
use App\Http\Controllers\TrainingrequestController;
use App\Http\Controllers\ScheduletrainingController;
use App\Http\Controllers\ExamquestionsController;
use App\Http\Controllers\ScheduleexamController;
use App\Http\Controllers\ExamController;
use App\Http\Controllers\EmployeeshiftmappController;
use App\Http\Controllers\TrainingfeedbackController;
use App\Http\Controllers\TrainingrequestapproveController;


// Training topics
Route::get('topic', [TopicController::class, 'create'])->name('topic');
Route::post('topicsave', [TopicController::class, 'save'])->name('topicsave');
Route::get('gettopicgrid', [TopicController::class, 'gettopicgrid'])->name('gettopicgrid');
Route::get('topicgrddataedit', [TopicController::class, 'topicgrddataedit'])->name('topicgrddataedit');
Route::get('topicdelete/{id}', [TopicController::class, 'destroy'])->name('topicdelete');

//Topics for Department
Route::get('departmenttopic', [DepartmenttopicController::class, 'create'])->name('departmenttopic');
Route::post('departmenttopicsave', [DepartmenttopicController::class, 'save'])->name('departmenttopicsave');
Route::get('getdepartmenttopicgrid', [DepartmenttopicController::class, 'getdepartmenttopicgrid'])->name('getdepartmenttopicgrid');
Route::get('departmenttopicgrddataedit', [DepartmenttopicController::class, 'topicgrddataedit'])->name('departmenttopicgrddataedit');
Route::get('departmenttopicdelete/{id}', [DepartmenttopicController::class, 'destroy'])->name('departmenttopicdelete');

//Training Request
Route::get('trainingrequest', [TrainingrequestController::class, 'create'])->name('trainingrequest');
Route::post('trainingrequestsave', [TrainingrequestController::class, 'save'])->name('trainingrequestsave');
Route::get('gettrainingrequestgrid', [TrainingrequestController::class, 'gettrainingrequestgrid'])->name('gettrainingrequestgrid');
Route::get('trainingrequestgrddataedit', [TrainingrequestController::class, 'topicgrddataedit'])->name('trainingrequestgrddataedit');
Route::get('trainingrequestdelete/{id}', [TrainingrequestController::class, 'destroy'])->name('trainingrequestdelete');

//schedule training
Route::get('scheduletraining', [ScheduletrainingController::class, 'index'])->name('scheduletraining');
Route::get('scheduletraininggriddata', [ScheduletrainingController::class, 'getscheduletraininggriddata'])->name('scheduletraininggriddata');
Route::get('scheduletrainingcreate/{id}', [ScheduletrainingController::class, 'create'])->name('scheduletrainingcreate');
Route::get('scheduletrainingview/{id}', [ScheduletrainingController::class, 'show'])->name('scheduletrainingview');
Route::get('scheduletrainingfromrequest', [ScheduletrainingController::class, 'index'])->name('scheduletrainingfromrequest');
Route::get('getrequesttraininggriddata', [ScheduletrainingController::class, 'getrequesttraininggriddata'])->name('getrequesttraininggriddata');
Route::get('scheduledetails/{id}', [ScheduletrainingController::class, 'scheduledetails']);
Route::post('updatestatus/{hdrid}/{lineid}', [ScheduletrainingController::class, 'updatestatus']);
Route::post('scheduletrainingsave', [ScheduletrainingController::class, 'scheduletrainingsave']);
Route::post('send-training-mail', [ScheduletrainingController::class, 'sendTrainingMail']);

Route::get('trainingreport', [ScheduletrainingController::class, 'reportindex'])->name('trainingreport');
Route::get('trainingreportdata', [ScheduletrainingController::class, 'trainingreportdata'])->name('trainingreportdata');

//Create Q&A for topics
Route::get('examquestions', [ExamquestionsController::class, 'index'])->name('examquestions');
Route::get('examquestionsgriddata', [ExamquestionsController::class, 'getexamquestionsgriddata'])->name('examquestionsgriddata');
Route::get('examquestionscreate/{id}', [ExamquestionsController::class, 'create'])->name('examquestionscreate');
Route::post('examquestionssave', [ExamquestionsController::class, 'save'])->name('examquestionssave');
Route::get('examquestionsview/{id}', [ExamquestionsController::class, 'show'])->name('examquestionsview');

//schedule exam
Route::get('scheduleexam', [ScheduleexamController::class, 'index'])->name('scheduleexam');
Route::get('scheduleexamgriddata', [ScheduleexamController::class, 'getscheduleexamgriddata'])->name('scheduleexamgriddata');
Route::get('scheduleexamcreate/{id}', [ScheduleexamController::class, 'create'])->name('scheduleexamcreate');
Route::post('scheduleexamsave', [ScheduleexamController::class, 'save'])->name('scheduleexamsave');
Route::get('scheduleexamview/{id}', [ScheduleexamController::class, 'show'])->name('scheduleexamview');

//attend exam
Route::get('exam', [ExamController::class, 'index'])->name('exam');
Route::get('examgriddata', [ExamController::class, 'getexamgriddata'])->name('examgriddata');
Route::get('examcreate/{id}', [ExamController::class, 'create'])->name('examcreate');
Route::post('examsave', [ExamController::class, 'save'])->name('examsave');
Route::get('examview/{id}', [ExamController::class, 'show'])->name('scheduleexamview');
Route::get('examschedulechk/{id}', [ExamController::class, 'examschedulechk'])->name('examschedulechk');

//Result
Route::get('generateresult', [ExamController::class, 'resultindex'])->name('generateresult');
Route::get('resultgenerate/{id}', [ExamController::class, 'resultgenerate'])->name('resultgenerate');
Route::get('result_chk/{id}', [ExamController::class, 'result_chk'])->name('result_chk');
Route::get('resultsview/{id}', [ExamController::class, 'resultsview'])->name('resultsview');
Route::get('finishedexamgriddata', [ExamController::class, 'getfinishedexamgriddata'])->name('finishedexamgriddata');
Route::get('examlist/{id}', [ExamController::class, 'examlist'])->name('examlist');
Route::get('examlistgriddata/{id}', [ExamController::class, 'examlistgriddata'])->name('examlistgriddata');

//getemployee by department
Route::get('getemployeebydepartment/{id}', [EmployeeshiftmappController::class, 'getemployee'])->name('getemp');
Route::get('shiftcheck/{id}', [EmployeeshiftmappController::class, 'shiftcheck'])->name('shiftcheck');

//validate Exam
Route::get('validateexam/{id}', [ExamController::class, 'validateexam'])->name('validateexam');
Route::post('validateexamsave', [ExamController::class, 'validatesave'])->name('validateexamsave');

//getemployee by topic
Route::get('getdepartmentsbytopic/{id}', [ScheduletrainingController::class, 'getdepartmentsbytopic'])->name('deptbytopic');
Route::get('getemployeelistbydept/{id}', [ScheduletrainingController::class, 'getemployeelistbydept'])->name('empbydept');
Route::get('getexamemployeelistbytopic/{id}', [ScheduleexamController::class, 'getexamemployeelistbytopic'])->name('empbytopic');
Route::get('getemployeelistbyzone/{id}', [ScheduletrainingController::class, 'getemployeelistbyzone'])->name('getemployeelistbyzone');
//feedback for training
Route::get('feedbackmail/{id}', [TrainingfeedbackController::class, 'sendmail'])->name('sendmail');
Route::get('feedbackcreate/{id}', [TrainingfeedbackController::class, 'create'])->name('feedbackform');
Route::post('feedbacksave', [TrainingfeedbackController::class, 'save'])->name('feedbacksave');
Route::get('scheduledtraining', [TrainingfeedbackController::class, 'index'])->name('scheduledtraining');
Route::get('scheduledtraininggriddata', [TrainingfeedbackController::class, 'getscheduledtraininggriddata'])->name('scheduledtraininggriddata');
Route::get('feedbackview/{id}', [TrainingfeedbackController::class, 'show'])->name('feedbackview');

//training request approve
Route::get('trainingrequestapprove', [TrainingrequestapproveController::class, 'index'])->name('trainingrequestapprove');
Route::get('trainingrequestapprovecreate/{id}', [TrainingrequestapproveController::class, 'create'])->name('trainingrequestapprovecreate');
Route::get('trainingrequestapproved/{id}', [TrainingrequestapproveController::class, 'approve_save'])->name('trainingrequestapproved');
Route::get('gettrainingrequestapprovegrid', [TrainingrequestapproveController::class, 'gettrainingrequestapprovegrid'])->name('gettrainingrequestapprovegrid');
Route::get('trainingrequestapprovegrddataedit', [TrainingrequestapproveController::class, 'topicgrddataedit'])->name('trainingrequestapprovegrddataedit');

//view result
Route::get('examresult', [ExamController::class, 'examresultindex'])->name('examresult');
Route::get('examresultgriddata', [ExamController::class, 'examresultgriddata'])->name('examresultgriddata');
Route::get('examresultview/{id}', [ExamController::class, 'examresultview'])->name('examresultview');


