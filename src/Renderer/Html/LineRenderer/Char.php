<?php

declare(strict_types=1);

namespace Jfcherng\Diff\Renderer\Html\LineRenderer;

use Jfcherng\Diff\Renderer\RendererConstant;
use Jfcherng\Diff\SequenceMatcher;
use Jfcherng\Diff\Utility\ReverseIterator;
use Jfcherng\Utility\MbString;

final class Char extends AbstractLineRenderer
{
    /**
     * {@inheritdoc}
     *
     * @return static
     */
    public function render(MbString $mbOld, MbString $mbNew): LineRendererInterface
    {
        $hunk = $this->getChangedExtentSegments($mbOld->toArray(), $mbNew->toArray());

        // reversely iterate hunk
        foreach (ReverseIterator::fromArray($hunk) as [$op, $i1, $i2, $j1, $j2]) {
            switch ($op) {
                case SequenceMatcher::OP_DEL:
                    $mbOld->str_enclose_i(RendererConstant::HTML_CLOSURES, $i1, $i2 - $i1);

                    break;
                case SequenceMatcher::OP_INS:
                    $mbNew->str_enclose_i(RendererConstant::HTML_CLOSURES, $j1, $j2 - $j1);

                    break;
                case SequenceMatcher::OP_REP:
                    $mbOld->str_enclose_i(RendererConstant::HTML_CLOSURES, $i1, $i2 - $i1);
                    $mbNew->str_enclose_i(RendererConstant::HTML_CLOSURES, $j1, $j2 - $j1);

                    break;
                default:
                    continue 2;
            }
        }

        return $this;
    }
}
