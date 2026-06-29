<?php

namespace App\Policies;

use App\Models\Report;
use App\Models\User;

class ReportPolicy
{
    public function viewAny(User $user): bool
    {
        return true; // semua peranan boleh lihat senarai (ditapis dalam controller)
    }

    public function view(User $user, Report $report): bool
    {
        if ($user->isAnalisis()) {
            return $report->created_by === $user->id;
        }

        return true; // pentadbir & penyelaras boleh lihat semua
    }

    public function create(User $user): bool
    {
        return $user->isAnalisis() || $user->isPentadbir();
    }

    public function update(User $user, Report $report): bool
    {
        if ($user->isPentadbir()) {
            return true;
        }

        return $user->isAnalisis()
            && $report->created_by === $user->id
            && in_array($report->workflow_status, [Report::STATUS_DRAF, Report::STATUS_PEMBETULAN], true);
    }

    public function submit(User $user, Report $report): bool
    {
        return $user->isAnalisis()
            && $report->created_by === $user->id
            && in_array($report->workflow_status, [Report::STATUS_DRAF, Report::STATUS_PEMBETULAN], true);
    }

    public function review(User $user, Report $report): bool
    {
        return $user->isPenyelaras()
            && $report->workflow_status === Report::STATUS_DIHANTAR;
    }

    public function delete(User $user, Report $report): bool
    {
        if ($user->isPentadbir()) {
            return true;
        }

        return $user->isAnalisis()
            && $report->created_by === $user->id
            && $report->workflow_status === Report::STATUS_DRAF;
    }
}
