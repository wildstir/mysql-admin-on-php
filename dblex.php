<?php
require("functions.php");
if ($_GET["expdf"]) {
   require('topdf/fpdf.php');
   connectDataBase();

   $pdf=new FPDF();
   $pdf->AddPage();
   $pdf->SetFont('Times','B',12);
   $pdf->Ln();
   $pdf->Ln();
   
   switch ($_GET["expdf"]) {
      case "PEO":
         constructPdf("PEOPLE", $pdf);
         break;
      case "SUB":
         constructPdf("SUBJECTS", $pdf);
         break;
      case "GRO":
         constructPdf("GROUPS", $pdf);
         break;
      case "MAR":
         constructPdf("MARKS", $pdf);
         break;
      case "USE":
         constructPdf("USERS", $pdf);
         break;
   }
}
else {
   echo "<script>location.href='./'</script>";
}

function constructPdf($tabpdf, $pdf)
   {
      $dynamicQuery = mysql_query("SELECT * FROM `$tabpdf`") or die(mysql_error());
      $dq = mysql_fetch_array($dynamicQuery);
      $len = 0;
      foreach($dq as $key => $value)
      {
         if ($len % 2 == 1)
         {
            $asot[($len-1)/2] = $key;
            $op = $key;
            if ($len == 1) $pdf->Cell(10,7,$key);
            else $pdf->Cell(35,7,$key);
            $pdf->Cell(2,7,"| ");
         }
         $len += 1;
      }
      $pdf->Ln();
      $pdf->Cell(450,7,"-----------------------------------------------------------------------------------------------------------------------------------------");
      $pdf->Ln();
      do 
      {
         for ($i = 0; $i < count($asot); $i++)
         {
            $op = $asot[$i];
            if ($i == 0) $pdf->Cell(10,7,$dq[$op]);
            else $pdf->Cell(35,7,$dq[$op]);
            $pdf->Cell(2,7,"| ");
         }
         $pdf->Ln();
      } while ($dq = mysql_fetch_array($dynamicQuery));
      $pdf->Ln();
      $date = date('m/d/Y h:i:s', time());
      $pdf->Cell(25, 7, $date);
   }
   $pdf->Output();
?>