<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;


class Laporan extends MX_Controller
{
	public $module = 'laporan';

	function __construct()
	{
		parent::__construct();
	}

	function index()
	{
		$module = $this->module;

		$data['module'] = $module;

		$data['title'] = 'Laporan';
		$page = $module.'/v_'.$module;
		$js = $module.'/js_'.$module;

		echo modules::run('template/loadview', $data, $page, $js);
	}

	public function cetak()
	{
		$provinsi 	= $this->input->post('provinsi');
		$kota 		= $this->input->post('kota');
		$kecamatan 	= $this->input->post('kecamatan');
		$kelurahan 	= $this->input->post('kelurahan');


		$this->db->select('ms.*,
			mu.name as inseminator,
			mu.photo as foto_petugas,
			mb.nama_bangsa as bangsa,');

		if ($provinsi!='') {
			$this->db->where('ms.provinsi', $provinsi);
		}
		if ($kota!='') {
			$this->db->where('ms.kota', $kota);
		}
		if ($kecamatan!='') {
			$this->db->where('ms.kecamatan', $kecamatan);
		}
		if ($kelurahan!='') {
			$this->db->where('ms.kelurahan', $kelurahan);
		}

		$datas = $this->db
		->join('m_users mu','mu.id_user=ms.id_inseminator','left')
		->join('m_bangsa mb','mb.id_bangsa=ms.id_bangsa','left')
		->get('m_sapi ms')->result_array();

		$filename	= 'Laporan Sicebun '.date('d-m-Y').'.xlsx';

		$spreadsheet = new Spreadsheet();
		$spreadsheet->setActiveSheetIndex(0)
		->setCellValue('A3', 'No')
		->setCellValue('B3', 'Nama Peternak')
		->setCellValue('C3', 'Alamat Peternak')
		->setCellValue('F3', 'Petugas IB')
		->setCellValue('G3', 'Nomor Sapi')
		->setCellValue('H3', 'Profil Sapi (Akseptor)')
		->setCellValue('O3', 'Straw')
		->setCellValue('Q3', 'IB 2')
		->setCellValue('U3', 'IB 3')
		->setCellValue('Y3', 'Status')
		->setCellValue('AB3', 'Kelahiran')
		->setCellValue('AF3', 'Gangguan Reproduksi')
		->setCellValue('AG3', 'Keterangan')
		->setCellValue('AL3', 'Rencana')

		->setCellValue('C4', 'Kelurahan/Desa')
		->setCellValue('D4', 'Kecamatan')
		->setCellValue('E4', 'Kabupaten')
		->setCellValue('H4', 'Bangsa')
		->setCellValue('I4', 'Umur (tahun)')
		->setCellValue('J4', 'Tgl Birahi')
		->setCellValue('K4', 'Waktu Birahi')
		->setCellValue('L4', 'Tgl IB')
		->setCellValue('M4', 'Waktu IB')
		->setCellValue('N4', 'Jarak Birahi - IB (menit)')
		->setCellValue('O4', 'Kode')
		->setCellValue('P4', 'Bangsa')
		->setCellValue('Q4', 'Tgl Birahi')
		->setCellValue('R4', 'Waktu Birahi')
		->setCellValue('S4', 'Tgl IB')
		->setCellValue('T4', 'Waktu IB')
		->setCellValue('U4', 'Tgl Birahi')
		->setCellValue('V4', 'Waktu Birahi')
		->setCellValue('W4', 'Tgl IB')
		->setCellValue('X4', 'Waktu IB')
		->setCellValue('X4', 'Waktu IB')
		->setCellValue('Y4', 'Tgl PKB')
		->setCellValue('Z4', 'Bunting')
		->setCellValue('AA4', 'Tidak Bunting')
		->setCellValue('AB4', 'Tgl')
		->setCellValue('AC4', 'Normal')
		->setCellValue('AD4', 'Tidak Normal')
		->setCellValue('AE4', 'Keterangan')
		->setCellValue('AG4', 'Foto Peternak')
		->setCellValue('AH4', 'Foto KTP Perternak')
		->setCellValue('AI4', 'Foto Petugas IB')
		->setCellValue('AJ4', 'Foto KTP Petugas IB')
		->setCellValue('AK4', 'Foto Sapi')
		->setCellValue('AL4', 'S/C')
		->setCellValue('AM4', 'CR')
		->setCellValue('AN4', 'CI');

		$sheet = $spreadsheet->getActiveSheet();
		$sheet->getStyle('A3:AN4')->getAlignment()->setHorizontal('center');
		$sheet->getStyle('A3:AN4')->getAlignment()->setVertical('center');

		$sheet->mergeCells('A3:A4');
		$sheet->mergeCells('B3:B4');
		$sheet->mergeCells('C3:E3');
		$sheet->mergeCells('F3:F4');
		$sheet->mergeCells('G3:G4');
		$sheet->mergeCells('H3:N3');
		$sheet->mergeCells('O3:P3');
		$sheet->mergeCells('Q3:T3');
		$sheet->mergeCells('U3:X3');
		$sheet->mergeCells('Y3:AA3');
		$sheet->mergeCells('AB3:AE3');
		$sheet->mergeCells('AF3:AF4');
		$sheet->mergeCells('AG3:AK3');
		$sheet->mergeCells('AL3:AN3');

		foreach ($datas as $key => $val) {
			$row = $key+5;

			$sheet->setCellValue('A'.$row, $key+1);
			$sheet->setCellValue('B'.$row, $val['peternak']);
			$sheet->setCellValue('C'.$row, $val['kelurahan']);
			$sheet->setCellValue('D'.$row, $val['kecamatan']);
			$sheet->setCellValue('E'.$row, $val['kota']);
			$sheet->setCellValue('F'.$row, $val['inseminator']);
			$sheet->setCellValue('G'.$row, $val['no_sapi']);
			$sheet->setCellValue('H'.$row, $val['bangsa']);
			$sheet->setCellValue('I'.$row, $val['tgl_lahir']);
			$sheet->setCellValue('J'.$row, $val['birahi_1']);
			$sheet->setCellValue('K'.$row, $val['birahi_1']);
			$sheet->setCellValue('L'.$row, $val['ib_1']);
			$sheet->setCellValue('M'.$row, $val['ib_1']);
			$sheet->setCellValue('N'.$row, $val['birahi_1'].$val['ib_1']);
			$sheet->setCellValue('O'.$row, $val['straw_1']);
			$sheet->setCellValue('P'.$row, '??');
			$sheet->setCellValue('Q'.$row, $val['birahi_2']);
			$sheet->setCellValue('R'.$row, $val['birahi_2']);
			$sheet->setCellValue('S'.$row, $val['ib_2']);
			$sheet->setCellValue('T'.$row, $val['ib_2']);
			$sheet->setCellValue('U'.$row, $val['birahi_3']);
			$sheet->setCellValue('V'.$row, $val['birahi_3']);
			$sheet->setCellValue('W'.$row, $val['ib_3']);
			$sheet->setCellValue('X'.$row, $val['ib_3']);
			$sheet->setCellValue('Y'.$row, $val['tgl_bunting']);
			$sheet->setCellValue('Z'.$row, $val['status'] == 'bunting' ? 'Ya' : '');
			$sheet->setCellValue('AA'.$row, $val['status'] == 'tidak_bunting' ? 'Ya' : '');
			$sheet->setCellValue('AB'.$row, $val['tgl_melahirkan']);
			$sheet->setCellValue('AC'.$row, empty($val['gangrep']) ? 'Ya' : '' );
			$sheet->setCellValue('AD'.$row, !empty($val['gangrep']) ? 'Ya' : '' );
			$sheet->setCellValue('AE'.$row, $val['kelahiran']);
			$sheet->setCellValue('AF'.$row, $val['gangrep']);

			$sheet->setCellValue('AG'.$row, $val['foto_pemilik']);
			$sheet->setCellValue('AH'.$row, '');
			$sheet->setCellValue('AI'.$row, $val['foto_petugas']);
			$sheet->setCellValue('AJ'.$row, '');
			$sheet->setCellValue('AK'.$row, $val['foto_sapi']);

			$sheet->setCellValue('AL'.$row, '');
			$sheet->setCellValue('AM'.$row, '');
			$sheet->setCellValue('AN'.$row, '');
		}

		foreach(range('A','Z') as $columnID) {
			$sheet->getColumnDimension($columnID)->setAutoSize(true);
			$sheet->getColumnDimension('A'.$columnID)->setAutoSize(true);
		}

		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment;filename="'.$filename.'"');
		header('Cache-Control: max-age=0');
		header('Cache-Control: max-age=1');
		header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
		header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
		header('Cache-Control: cache, must-revalidate');
		header('Pragma: public');

		$writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
		$writer->save('php://output');
		exit;
	}

}