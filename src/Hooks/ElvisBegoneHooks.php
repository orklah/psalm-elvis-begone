<?php declare(strict_types=1);

namespace Orklah\ElvisBegone\Hooks;

use PhpParser\Node\Expr\Ternary;
use Psalm\FileManipulation;
use Psalm\Node\VirtualNode;
use Psalm\Plugin\EventHandler\AfterExpressionAnalysisInterface;
use Psalm\Plugin\EventHandler\Event\AfterExpressionAnalysisEvent;

class ElvisBegoneHooks implements AfterExpressionAnalysisInterface
{
    public static function afterExpressionAnalysis(AfterExpressionAnalysisEvent $event): ?bool
    {
        if (!$event->getCodebase()->alter_code) {
            return true;
        }

        $expr = $event->getExpr();
        if ($expr instanceof VirtualNode) {
            // This is a node created by Psalm for analysis purposes. This is not interesting
            return true;
        }

        if (!$expr instanceof Ternary || $expr->if !== null) {
            //we're not in an elvis operator :(
            return true;
        }

        $node_provider = $event->getStatementsSource()->getNodeTypeProvider();

        $cond_type = $node_provider->getType($expr->cond);

        if ($cond_type === null) {
            return true;
        }

        if ($cond_type->from_docblock) {
            return true;// this is risky
        }

        if (!$cond_type->isNullable()) {
            //if it's not nullable, null coalesce is useless
            return true;
        }
        $tmp_cond_type = clone $cond_type;
        $tmp_cond_type->removeType('null');

        if (!$tmp_cond_type->isAlwaysTruthy()) {
            //The condition might be true, we have to keep elvis :(
            return true;
        }


        $startPos = $expr->cond->getEndFilePos() + 1;
        $endPos = $expr->else->getStartFilePos();

        $file_manipulation = new FileManipulation($startPos, $endPos, ' ?? ');
        $event->setFileReplacements([$file_manipulation]);

        return true;
    }
}
