<?php
/**
 * Created by PhpStorm.
 * User: Abdul Rohim
 * Date: 9/9/2020
 * Time: 10:56 AM
 */

namespace App\Helpers;

use App\Model\CertificateRegList;
use App\Model\Coach;
use App\Model\DignityMember;
use App\Model\Examiner;
use App\Model\Log;
use App\Model\LogTransaction;
use App\Model\Referee;
use App\Model\Shipping;
use App\Model\UserCertificate;
use App\User;
use App\Model\Athlete;
use App\Model\UserReference;
use GeneratingCertificate;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;
use Storage;
use DB;

class GeneralHelpers
{
    const FIRST_THUMBNAIL = 300; //KB

    public function timestamp()
    {
        return date('Y-m-d H:i:s');
    }

    function idn_date_format($date,$withDay=true) {
        $result = null;
        if (isset($date)) {
          $BulanIndo = array("Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember");

          $tahun = substr($date, 0, 4);
          $bulan = substr($date, 5, 2);
          $tgl = substr($date, 8, 2);

          $date_ = explode(' ', $date);
          $day = date('D', strtotime($date_[0]));
          $dayList = array(
            'Sun' => 'Minggu',
            'Mon' => 'Senin',
            'Tue' => 'Selasa',
            'Wed' => 'Rabu',
            'Thu' => 'Kamis',
            'Fri' => "Jum'at",
            'Sat' => 'Sabtu'
          );

          $result = $tgl . " " . $BulanIndo[((int)$bulan) - 1] . " " . $tahun . " " . substr($date, 11, 9);
          if ($withDay) $result = $dayList[$day].", ".$result;
        }
        return $result;
    }

    function date_format_id($date) {
        $BulanIndo = array("Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember");

        $tahun = substr($date, 0, 4);
        $bulan = substr($date, 5, 2);
        $tgl = substr($date, 8, 2);

        $result = $tgl . " " . $BulanIndo[(int)$bulan - 1] . " " . $tahun;
        return $result;
    }

    function date_format_inv($date) {
        $BulanIndo = array("Jan", "Feb", "Mar", "Apr", "Mei", "Jun", "Jul", "Agu", "Sep", "Okt", "Nov", "Des");

        $tahun = substr($date, 0, 4);
        $bulan = substr($date, 5, 2);
        $tgl = substr($date, 8, 2);

        $result = $tgl . "-" . $BulanIndo[(int)$bulan - 1] . "-" . $tahun;
        return $result;
    }

    function date_format_dmY($date) {
        $tahun = substr($date, 0, 4);
        $bulan = substr($date, 5, 2);
        $tgl = substr($date, 8, 2);

        $result = $tgl . "-" . $bulan . "-" . $tahun;
        return $result;
    }

    function convert_date_number_day($date) {
        $day = date('D', strtotime($date));
        $dayList = array(
            'Sun' => 6,
            'Mon' => 0,
            'Tue' => 1,
            'Wed' => 2,
            'Thu' => 3,
            'Fri' => 4,
            'Sat' => 5
        );

        return (string)$dayList[$day];
    }

    function convert_date_name_month($date) {
        $month = date('M', strtotime($date));
        $monthList = array(
            'Jan' => "Januari",
            'Feb' => "Februari",
            'Mar' => "Maret",
            'Apr' => "April",
            'May' => "Mei",
            'Jun' => "Juni",
            'Jul' => "Juli",
            'Aug' => "Agustus",
            'Sep' => "September",
            'Oct' => "Oktober",
            'Nov' => "November",
            'Dec' => "Desember"
        );
        return (string)$monthList[$month];
    }

    function convert_date_name_day($date) {
        $day = date('D', strtotime($date));
        $dayList = array(
            'Sun' => "Minggu",
            'Mon' => "Senin",
            'Tue' => "Selasa",
            'Wed' => "Rabu",
            'Thu' => "Kamis",
            'Fri' => "Jumat",
            'Sat' => "Sabtu"
        );

        return (string)$dayList[$day];
    }

    function convert_date_to_year($date) {
        $d1 = new \DateTime(date('Y-m-d'));
        $d2 = new \DateTime($date);

        $diff = $d2->diff($d1);

        return $diff->y;
    }

    function debt_age($payment)
    {
        if ($payment == 2) {
            $number = 30;
        } elseif ($payment == 3) {
            $number = 60;
        } elseif ($payment == 4) {
            $number = 90;
        } else {
            $number = 140;
        }

        return $number;
    }

    public function calculate_day_between_two_date($first_date, $last_date)
    {
        $date1 = new \DateTime($first_date);
        $date2 = new \DateTime($last_date);
        $interval = $date1->diff($date2);

        return $interval->days;
    }

    function currency($total)
    {
        return 'Rp.' . number_format($total, 0, '', '.');
    }

    function raw_currency($total)
    {
        return number_format($total, 0, '', '.');
    }

    /**
     * search date of week by date
     *
     * @param $date
     * @return int
     */
    function weekOfDay() {
        $dt_min = new \DateTime("last saturday");
        $dt_min->modify('+1 day');
        $dt_max = clone($dt_min);
        $dt_max->modify('+6 days');

        return $dt_min->format('Y-m-d').' '.$dt_max->format('Y-m-d');
    }

    public function payment_type($type){
        switch ($type) {
            case 1:
                $val = 'Cash';
                break;
            case 2:
                $val = 'TOP';
                break;
            case 3:
                $val = 'Hutang (31 - 60 Hari)';
                break;
            case 4:
                $val = 'Hutang (61 - 90 Hari)';
                break;
            case 5:
                $val = 'Hutang (> 90 Hari)';
                break;
        }

        return $val;
    }

    public function next_date($date)
    {
        $result = date('Y-m-d', strtotime('+1 day', strtotime($date)));

        return $result;
    }

    public function custom_next_date($date, $payment, $type)
    {
        $number = $this->debt_age($payment);

        $result = date('Y-m-d', strtotime('+'.$number . ' ' . $type, strtotime($date)));

        return $result;
    }

    function getRomawi($month){
        switch ($month){
            case 1:
                return "I";
                break;
            case 2:
                return "II";
                break;
            case 3:
                return "III";
                break;
            case 4:
                return "IV";
                break;
            case 5:
                return "V";
                break;
            case 6:
                return "VI";
                break;
            case 7:
                return "VII";
                break;
            case 8:
                return "VIII";
                break;
            case 9:
                return "IX";
                break;
            case 10:
                return "X";
                break;
            case 11:
                return "XI";
                break;
            case 12:
                return "XII";
                break;
        }
    }

    public function invoice_number($shipping_name, $payment_type, $invoice_type)
    {
        $date = date_create(date('Y-m-d'));
        $result = Shipping::select('invoice_number')
//            ->where(\Illuminate\Support\Facades\DB::raw("YEAR(invoice_date)"), date_format($date, 'Y'))
//            ->where('invoice_year', date('Y'))
            ->whereYear('invoice_date', date('Y'))
            ->where('invoice_type', $invoice_type)
            ->orderBy('id', 'DESC')->first();

        $query = Shipping::select('invoice_number')
            ->whereYear('invoice_date', date('Y'))
            ->where('invoice_type', $invoice_type)
            ->orderBy('id', 'DESC');

        $toSql = Str::replaceArray('?', $query->getBindings(), $query->toSql());

        if (!isset($result)) {
            $result = Shipping::select('invoice_number')
                ->where('invoice_year', date('Y'))
                ->where('invoice_type', (string)$invoice_type)
                ->orderBy('id', 'DESC')->first();

            $query = Shipping::select('invoice_number')
                ->where('invoice_year', date('Y'))
                ->where('invoice_type', (string)$invoice_type)
                ->orderBy('id', 'DESC');

            $toSql = Str::replaceArray('?', $query->getBindings(), $query->toSql());

            if (!isset($result)) {
                $result = Shipping::select('invoice_number')
                    ->where('invoice_number', 'LIKE', "%".date('y').".%")
                    ->where('invoice_type', (string)$invoice_type)
                    ->orderBy('id', 'DESC')->first();

                $query = Shipping::select('invoice_number')
                    ->where('invoice_number', 'LIKE', "%".date('y').".%")
                    ->where('invoice_type', (string)$invoice_type)
                    ->orderBy('id', 'DESC');

                $toSql = Str::replaceArray('?', $query->getBindings(), $query->toSql());

                if (!isset($result)) {
                    $result = Shipping::select('invoice_number')
                        ->where('invoice_number', 'LIKE', "%".date('y').".%")
                        ->where('type', (int)$invoice_type)
                        ->orderBy('id', 'DESC')->first();

                    $query = Shipping::select('invoice_number')
                        ->where('invoice_number', 'LIKE', "%".date('y').".%")
                        ->where('type', (int)$invoice_type)
                        ->orderBy('id', 'DESC');

                    $toSql = Str::replaceArray('?', $query->getBindings(), $query->toSql());
                }
            }
        }

        $shipping = Shipping::where('type', (int)$invoice_type)->orderBy('id', 'DESC')->first();
        $shipping->json_inv_number = json_encode($toSql);
        $shipping->result_last_number = json_encode($result);
        $shipping->save();

        if (isset($result)) {
//            $invoice_number = explode('/', $result->invoice_number);
//            $last_number = (int)substr($invoice_number[4], 3, 6);
            $last_number = (int)substr($result->invoice_number, -6);
            $last_number += 1;

            if ($last_number < 100) {
                $number = str_pad($last_number, 6, "0", STR_PAD_LEFT);
            } else
                if ($last_number < 1000) {
                    $number = str_pad($last_number, 6, "0", STR_PAD_LEFT);
                } else
                    if ($last_number < 10000) {
                        $number = str_pad($last_number, 6, "0", STR_PAD_LEFT);
                    } else
                        if ($last_number < 100000) {
                            $number = str_pad($last_number, 6, "0", STR_PAD_LEFT);
                        } else
                            if ($last_number < 1000000) {
                                $number = str_pad($last_number, 6, "0", STR_PAD_LEFT);
                            }
        } else {
            $number = '000001';
        }

        if ($payment_type == 1) {
            $payment_code = 'MAS-OS/';
        } else {
            $payment_code = 'MAS-OP/';
        }

        if ($invoice_type == '1') {
            $code = 'DL';
        } elseif ($invoice_type == '2') {
            $code = 'CH';
        } else {
            $code = 'UD';
        }

        return $payment_code.str_replace(' ','-',$shipping_name).'/'.$this->getRomawi(date('m')).'/'.$code.'/'.date('y').'.'.$number;
    }

    public function input_currency_converter($input)
    {
        $explode = explode('Rp', $input[0]);

        $string = htmlentities($explode[1], null, 'utf-8');
        $content = str_replace("&nbsp;", "", $string);
        $content = html_entity_decode($content);
        $result =  str_replace('.', '', $content);

        return (int)$result;
    }

    public function formatTerbilang($angka)
    {
        $nilai = abs($angka);
        $huruf = array("", "satu", "dua", "tiga", "empat", "lima", "enam", "tujuh", "delapan", "sembilan", "sepuluh", "sebelas");
        $temp = "";
        if ($nilai < 12) {
            $temp = " " . $huruf[$nilai];
        } else if ($nilai < 20) {
            $temp = $this->formatTerbilang($nilai - 10) . " belas";
        } else if ($nilai < 100) {
            $temp = $this->formatTerbilang($nilai / 10) . " puluh" . $this->formatTerbilang($nilai % 10);
        } else if ($nilai < 200) {
            $temp = " seratus" . $this->formatTerbilang($nilai - 100);
        } else if ($nilai < 1000) {
            $temp = $this->formatTerbilang($nilai / 100) . " ratus" . $this->formatTerbilang($nilai % 100);
        } else if ($nilai < 2000) {
            $temp = " seribu" . $this->formatTerbilang($nilai - 1000);
        } else if ($nilai < 1000000) {
            $temp = $this->formatTerbilang($nilai / 1000) . " ribu" . $this->formatTerbilang($nilai % 1000);
        } else if ($nilai < 1000000000) {
            $temp = $this->formatTerbilang($nilai / 1000000) . " juta" . $this->formatTerbilang($nilai % 1000000);
        } else if ($nilai < 1000000000000) {
            $temp = $this->formatTerbilang($nilai / 1000000000) . " milyar" . $this->formatTerbilang(fmod($nilai, 1000000000));
        } else if ($nilai < 1000000000000000) {
            $temp = $this->formatTerbilang($nilai / 1000000000000) . " trilyun" . $this->formatTerbilang(fmod($nilai, 1000000000000));
        }
        return ucfirst($temp);
    }

}
