import axios from 'axios';
import type { ApiResponse, User, Sapi, Bangsa, Directory, Consultant } from '../types/api';


// Create Axios Instance
const apiClient = axios.create({
  baseURL: import.meta.env.DEV ? '' : 'https://sicebun.jaslit.com',
});

// Helper to convert object to URLSearchParams (x-www-form-urlencoded)
const toFormBody = (params: Record<string, any>): URLSearchParams => {
  const form = new URLSearchParams();
  Object.keys(params).forEach((key) => {
    if (params[key] !== undefined && params[key] !== null) {
      form.append(key, String(params[key]));
    }
  });
  return form;
};

// --- AUTH SERVICE ---
export const authService = {
  login: async (email: string, password: string): Promise<ApiResponse<User>> => {
    const response = await apiClient.post(
      '/api/app/login',
      toFormBody({ email, password }),
      { headers: { 'Content-Type': 'application/x-www-form-urlencoded' } }
    );
    return response.data;
  },

  register: async (
    name: string,
    address: string,
    phone: string,
    email: string,
    password: string,
    photo?: File
  ): Promise<ApiResponse<any>> => {
    const formData = new FormData();
    formData.append('name', name);
    formData.append('address', address);
    formData.append('phone', phone);
    formData.append('email', email);
    formData.append('password', password);
    if (photo) {
      formData.append('photo', photo);
    }
    const response = await apiClient.post('/api/app/register', formData, {
      headers: { 'Content-Type': 'multipart/form-data' },
    });
    return response.data;
  },

  updateProfile: async (
    id: string,
    name: string,
    address: string,
    phone: string,
    email: string
  ): Promise<ApiResponse<User>> => {
    const response = await apiClient.post(
      '/api/app/update_user',
      toFormBody({ id, name, address, phone, email }),
      { headers: { 'Content-Type': 'application/x-www-form-urlencoded' } }
    );
    return response.data;
  },

  changePassword: async (
    id: string,
    lama: string,
    baru: string,
    konfirm: string
  ): Promise<ApiResponse<any>> => {
    const response = await apiClient.post(
      '/api/app/change_password',
      toFormBody({ id, lama, baru, konfirm }),
      { headers: { 'Content-Type': 'application/x-www-form-urlencoded' } }
    );
    return response.data;
  },

  changePhoto: async (id: string, foto: File): Promise<ApiResponse<User>> => {
    const formData = new FormData();
    formData.append('id', id);
    formData.append('foto', foto);
    const response = await apiClient.post('/api/app/change_foto_user', formData, {
      headers: { 'Content-Type': 'multipart/form-data' },
    });
    return response.data;
  },
};

// --- DASHBOARD SERVICE ---
export interface DashboardData {
  jumlah_sapi: string;
  status_sapi: Array<{
    initial: string;
    nama: string;
    jumlah: string;
    sapi: Sapi[];
  }>;
  direktori: Directory[];
  bangsa: Bangsa[];
  konsultan: Consultant[];
}

export const dashboardService = {
  getDashboard: async (id_user: string): Promise<ApiResponse<DashboardData>> => {
    const response = await apiClient.post(
      '/api/app/dashboard',
      toFormBody({ id_user }),
      { headers: { 'Content-Type': 'application/x-www-form-urlencoded' } }
    );
    return response.data;
  },
};

// --- SAPI (CATTLE) SERVICE ---
export const sapiService = {
  getSapiList: async (id_user: string, status?: string): Promise<ApiResponse<Sapi[]>> => {
    const response = await apiClient.post(
      '/api/app/sapi',
      toFormBody({ id_user, status }),
      { headers: { 'Content-Type': 'application/x-www-form-urlencoded' } }
    );
    return response.data;
  },

  getSapiDetail: async (
    id_sapi: string,
    no_sapi?: string,
    id_user?: string
  ): Promise<ApiResponse<Sapi>> => {
    const response = await apiClient.post(
      '/api/app/get_sapi',
      toFormBody({ id_sapi, no_sapi, id_user }),
      { headers: { 'Content-Type': 'application/x-www-form-urlencoded' } }
    );
    return response.data;
  },

  addSapi: async (payload: {
    id_inseminator: string;
    no_sapi: string;
    peternak: string;
    alamat: string;
    provinsi: string;
    kota: string;
    kecamatan: string;
    kelurahan: string;
    bangsa: string;
    tgl_lahir: string;
    birahi_1: string;
    ib_1: string;
    straw: string;
    foto_pemilik?: File | null;
    foto_sapi?: File | null;
  }): Promise<ApiResponse<any>> => {
    const formData = new FormData();
    Object.keys(payload).forEach((key) => {
      const val = payload[key as keyof typeof payload];
      if (val !== undefined && val !== null) {
        if (val instanceof File) {
          formData.append(key, val);
        } else {
          formData.append(key, String(val));
        }
      }
    });
    const response = await apiClient.post('/api/app/add_sapi', formData, {
      headers: { 'Content-Type': 'multipart/form-data' },
    });
    return response.data;
  },

  updateSapiStatus: async (params: {
    id_sapi: string;
    status: string;
    tgl_birahi?: string;
    tgl_ib?: string;
    ket?: string;
    straw?: string;
    tgl_melahirkan?: string;
  }): Promise<ApiResponse<any>> => {
    const response = await apiClient.post(
      '/api/app/update_sapi',
      toFormBody(params),
      { headers: { 'Content-Type': 'application/x-www-form-urlencoded' } }
    );
    return response.data;
  },

  changeSapiPhoto: async (
    id: string,
    column: 'foto_pemilik' | 'foto_sapi',
    foto: File
  ): Promise<ApiResponse<Sapi>> => {
    const formData = new FormData();
    formData.append('id', id);
    formData.append('column', column);
    formData.append('foto', foto);
    const response = await apiClient.post('/api/app/change_foto_sapi', formData, {
      headers: { 'Content-Type': 'multipart/form-data' },
    });
    return response.data;
  },

  deleteSapi: async (id_sapi: string): Promise<ApiResponse<any>> => {
    const response = await apiClient.post(
      '/api/app/delete_sapi',
      toFormBody({ id_sapi }),
      { headers: { 'Content-Type': 'application/x-www-form-urlencoded' } }
    );
    return response.data;
  },
};

// --- CATALOG & INFO SERVICE ---
export const catalogService = {
  getBreeds: async (): Promise<ApiResponse<Bangsa[]>> => {
    const response = await apiClient.get('/api/app/bangsa');
    return response.data;
  },

  getDirectories: async (): Promise<ApiResponse<Directory[]>> => {
    const response = await apiClient.get('/api/app/direktori');
    return response.data;
  },

  getDirectoryDetail: async (id_direktori: string): Promise<ApiResponse<Directory>> => {
    const response = await apiClient.post(
      '/api/app/get_direktori',
      toFormBody({ id_direktori }),
      { headers: { 'Content-Type': 'application/x-www-form-urlencoded' } }
    );
    return response.data;
  },

  getConsultants: async (): Promise<ApiResponse<Consultant[]>> => {
    const response = await apiClient.get('/api/app/konsultan');
    return response.data;
  },

  getConsultantDetail: async (id_konsultan: string): Promise<ApiResponse<Consultant>> => {
    const response = await apiClient.post(
      '/api/app/get_konsultan',
      toFormBody({ id_konsultan }),
      { headers: { 'Content-Type': 'application/x-www-form-urlencoded' } }
    );
    return response.data;
  },
};

// --- ALARM SERVICE ---
export const alarmService = {
  getAlarms: async (id_user: string): Promise<ApiResponse<Sapi[]>> => {
    const response = await apiClient.post(
      '/api/app/alarm',
      toFormBody({ id_user }),
      { headers: { 'Content-Type': 'application/x-www-form-urlencoded' } }
    );
    return response.data;
  },
};
