export interface User {
  id_user: string;
  email: string;
  name: string;
  address: string;
  phone: string;
  peran: 'admin' | 'inseminator' | 'peneliti';
  photo: string;
}

export interface Bangsa {
  id_bangsa: string;
  nama_bangsa: string;
}

export interface Sapi {
  id_sapi: string;
  id_inseminator: string;
  no_sapi: string;
  peternak: string;
  alamat: string;
  provinsi?: string;
  kota?: string;
  kecamatan?: string;
  kelurahan?: string;
  id_bangsa?: string;
  bangsa?: string; // Joined breed name
  tgl_lahir?: string;
  birahi_1?: string;
  ib_1?: string;
  straw_1?: string;
  ket_1?: string;
  birahi_2?: string;
  ib_2?: string;
  straw_2?: string;
  ket_2?: string;
  birahi_3?: string;
  ib_3?: string;
  straw_3?: string;
  ket_3?: string;
  is_bunting?: '0' | '1';
  bunting?: string;
  tidak_bunting?: string;
  gangrep?: string;
  is_melahirkan?: '0' | '1';
  tgl_melahirkan?: string;
  kelahiran?: string;
  foto_pemilik?: string;
  foto_sapi?: string;
  status: 'ib1' | 'ib2' | 'ib3' | 'bunting' | 'tidak_bunting' | 'gangrep' | 'kelahiran';
  created_date?: string;
  
  // Computed fields from API
  first_ib?: string;
  first_birahi?: string;
  umur_kebuntingan?: string;
  prediksi_tgl_lahir?: string;
  ib_calving?: string;
  
  // Layout computed fields for Alarm view
  id_alarm?: string;
  desc?: string;
  title?: string;
  active?: 'on' | 'off';
  loop?: 'on' | 'off';
  time?: string;
}

export interface GaleriItem {
  id_galeri: string;
  id_direktori: string;
  file: string;
}

export interface Directory {
  id_direktori: string;
  nama_direktori: string;
  nama?: string;
  cover: string;
  desc?: string;
  informasi?: string;
  galeri?: GaleriItem[];
}

export interface Consultant {
  id_konsultan: string;
  nama: string;
  keahlian: string;
  kontak: string;
  telp?: string;
  foto: string;
  is_active?: string;
}

export interface ApiResponse<T> {
  status: boolean;
  msg: string;
  data: T;
}
