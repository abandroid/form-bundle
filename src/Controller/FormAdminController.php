<?php

/*
 * (c) Jeroen van den Enden <info@endroid.nl>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Endroid\FormBundle\Controller;

use Endroid\FormBundle\Entity\Form;
use PHPExcel;
use PHPExcel_Exception;
use PHPExcel_Writer_Excel2007;
use Sonata\AdminBundle\Controller\CRUDController;
use Symfony\Component\HttpFoundation\Response;

class FormAdminController extends CRUDController
{
    /**
     * Generates an export of all results.
     *
     * @param Form $form
     *
     * @return Response
     *
     * @throws PHPExcel_Exception
     */
    public function resultsAction(Form $form)
    {
        $excel = new PHPExcel();
        $excel->setActiveSheetIndex(0);

        $rowNumber = 1;
        foreach ($form->getResults() as $result) {
            if (1 == $rowNumber) {
                $ord = ord('A');
                foreach ($result->getEntries() as $entry) {
                    $excel->getActiveSheet()->setCellValue(chr($ord).$rowNumber, $entry->getField()->getTitle());
                    ++$ord;
                }
                $excel->getActiveSheet()->setCellValue(chr($ord).$rowNumber, 'Datum');
                ++$rowNumber;
            }
            $ord = ord('A');
            foreach ($result->getEntries() as $entry) {
                $excel->getActiveSheet()->setCellValue(chr($ord).$rowNumber, implode(', ', (array) $entry->getValue()));
                ++$ord;
            }
            $excel->getActiveSheet()->setCellValue(chr($ord).$rowNumber, $result->getDatetimeAdded()->format('Y-m-d H:i:s'));
            ++$rowNumber;
        }

        $fileName = preg_replace('#\s+#i', '_', $form->getTitle()).'_'.$form->getId().'_'.date('YmdHis').'.xlsx';

        $writer = new PHPExcel_Writer_Excel2007($excel);
        ob_start();
        $writer->save('php://output');
        $content = ob_get_clean();
        $response = new Response();
        $response->headers->set('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        $response->headers->set('Content-Disposition', 'attachment;filename="'.$fileName.'"');
        $response->headers->set('Cache-Control', 'max-age=0');
        $response->setContent($content);

        return $response;
    }
}
