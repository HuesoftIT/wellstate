<?php

namespace App\Observers;

use App\Models\Branch;
use Illuminate\Support\Facades\Storage;

class BranchObserver
{
    public function creating(Branch $branch)
    {
        if (request()->hasFile('image')) {
            $branch->image = Branch::uploadAndResize(
                request()->file('image')
            );

            dd($branch->image);
        }
    }

    public function updating(Branch $branch)
    {
        if (request()->hasFile('image')) {

            // Xóa ảnh cũ
            if ($branch->image) {
                Storage::disk('public')->delete($branch->image);
            }

            $branch->image = Branch::uploadAndResize(
                request()->file('image')
            );
        }
    }

    public function deleted(Branch $branch)
    {
        // Xóa ảnh khi soft delete (tuỳ bạn)
        if ($branch->image) {
            Storage::disk('public')->delete($branch->image);
        }
    }
}
