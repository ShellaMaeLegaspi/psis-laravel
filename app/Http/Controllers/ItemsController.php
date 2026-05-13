<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ItemsController extends Controller
{
    /**
     * Determine the active PSIS fund-class database connection.
     */
    private function psisConn(): string
    {
        return match (session('FundClass')) {
            'BDD' => 'psis_bdd',
            'TRUST' => 'psis_trust',
            'RCEP' => 'psis_rcep',
            default => 'psis_corporate',
        };
    }

    /**
     * AJAX: Search main articles (LIB_Items) by criteria.
     * Maps to CI: Items->search_main_articles / Items_Model->getHeaders
     */
    public function searchMainArticles(Request $request)
    {
        $criteria = (array) $request->input('criteria', []);

        $query = DB::connection($this->psisConn())
            ->table('LIB_Items as A')
            ->select([
                'A.ItemID',
                'A.MajorArticleID',
                'A.OECode',
                'A.ACCode',
                'A.MajorArticleCode',
                'A.MainArticleCode',
                'A.MainArticleDesc',
                'A.ItemCode',
                'A.SpecDetails',
                'A.SuggestedSpecs',
                'A.UnitName',
                'A.UnitPrice',
                'A.ReferenceNo',
                'A.Common',
                'A.PPE',
                'A.Technical',
                'A.ReOrderPoint',
                'A.UsefulLife',
                'A.Contingency',
                'A.InActive',
                'A.CreatedBy',
                'A.DateCreated',
                'A.UpdatedBy',
                'A.DateUpdated',
                'A.ProCode',
                'A.IsSemiExpendable',
                DB::raw("RIGHT('000'+ISNULL(CONVERT(VARCHAR,A.MajorArticleCode), ''),3) AS MajorArticleCode"),
                'B.MajorArticleDesc',
            ])
            ->leftJoin('LIB_Major_Articles as B', function ($join) {
                $join->on('A.ACCode', '=', 'B.ACCode')
                    ->on('A.MajorArticleCode', '=', 'B.MajorArticleCode');
            });

        if (isset($criteria['SpecDetails']) && trim((string) $criteria['SpecDetails']) !== '') {
            $words = preg_split('/\s+/', trim((string) $criteria['SpecDetails'])) ?: [];
            foreach ($words as $word) {
                $query->where('A.SpecDetails', 'LIKE', '%' . $word . '%');
            }
        }

        if (isset($criteria['ItemCode']) && trim((string) $criteria['ItemCode']) !== '') {
            $query->where('A.ItemCode', (string) $criteria['ItemCode']);
        }

        if (isset($criteria['ItemID']) && is_numeric($criteria['ItemID'])) {
            $query->where('A.ItemID', (int) $criteria['ItemID']);
        }

        if (isset($criteria['OECode']) && trim((string) $criteria['OECode']) !== '') {
            $query->where('A.OECode', (string) $criteria['OECode']);
        }

        if (isset($criteria['ACCode']) && trim((string) $criteria['ACCode']) !== '') {
            $query->where('A.ACCode', (string) $criteria['ACCode']);
        }

        if (array_key_exists('InActive', $criteria)) {
            $query->where('A.InActive', (int) $criteria['InActive']);
        }

        $limit = (isset($criteria['NoLimit']) && (int) $criteria['NoLimit'] === 1) ? null : 50;

        try {
            if ($limit !== null) {
                $query->limit($limit);
            }

            $items = $query->get()->map(function ($row) {
                $item = (array) $row;
                $item['MainArticleDesc'] = html_entity_decode((string) ($item['MainArticleDesc'] ?? ''));
                $item['SpecDetails'] = html_entity_decode((string) ($item['SpecDetails'] ?? ''));
                $item['SuggestedSpecs'] = html_entity_decode((string) ($item['SuggestedSpecs'] ?? ''));
                return $item;
            })->toArray();

            return response()->json($items);
        } catch (\Throwable $e) {
            return response()->json([]);
        }
    }
}

