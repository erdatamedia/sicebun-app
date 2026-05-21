import React, { useState } from 'react';
import { useApp } from '../context/AppContext';
import { sapiService } from '../services/api';
import { ArrowLeft, ArrowRight, Save, MapPin, Tag, ShieldAlert, Calendar, Camera } from 'lucide-react';

export const AddSapiView: React.FC = () => {
  const { user, dashboardData, navigateTo, refreshSapiList, refreshDashboard } = useApp();
  const [step, setStep] = useState(1);
  const [loading, setLoading] = useState(false);
  const [error, setError] = useState<string | null>(null);

  // Form Fields
  const [noSapi, setNoSapi] = useState('');
  const [peternak, setPeternak] = useState('');
  const [alamat, setAlamat] = useState('');
  const [provinsi] = useState('Jawa Tengah');
  const [kota] = useState('Pemalang');

  const [kecamatan, setKecamatan] = useState('Pemalang');
  const [kelurahan, setKelurahan] = useState('Bojongbata');
  const [bangsa, setBangsa] = useState('');
  const [tglLahir, setTglLahir] = useState('');
  
  // IB history
  const [tglBirahi, setTglBirahi] = useState('');
  const [tglIb, setTglIb] = useState('');
  const [straw, setStraw] = useState('');

  // Files
  const [fotoPemilik, setFotoPemilik] = useState<File | null>(null);
  const [fotoPemilikPreview, setFotoPemilikPreview] = useState<string | null>(null);
  const [fotoSapi, setFotoSapi] = useState<File | null>(null);
  const [fotoSapiPreview, setFotoSapiPreview] = useState<string | null>(null);

  const handleFileChange = (e: React.ChangeEvent<HTMLInputElement>, type: 'pemilik' | 'sapi') => {
    if (e.target.files && e.target.files[0]) {
      const file = e.target.files[0];
      if (type === 'pemilik') {
        setFotoPemilik(file);
        setFotoPemilikPreview(URL.createObjectURL(file));
      } else {
        setFotoSapi(file);
        setFotoSapiPreview(URL.createObjectURL(file));
      }
    }
  };

  const handleNext = () => {
    setError(null);
    if (step === 1) {
      if (!noSapi || !peternak || !alamat) {
        setError('Mohon lengkapi data Tag Sapi, Nama Peternak, dan Alamat.');
        return;
      }
    } else if (step === 2) {
      if (!bangsa || !tglLahir) {
        setError('Mohon lengkapi jenis bangsa sapi dan perkiraan tanggal lahir.');
        return;
      }
    } else if (step === 3) {
      if (!tglBirahi || !tglIb || !straw) {
        setError('Mohon lengkapi Riwayat IB (Tanggal Birahi, Tanggal IB, dan Nomor Straw).');
        return;
      }
    }
    setStep(step + 1);
  };

  const handleBack = () => {
    setError(null);
    setStep(step - 1);
  };

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault();
    if (!user) return;
    setLoading(true);
    setError(null);

    try {
      const response = await sapiService.addSapi({
        id_inseminator: user.id_user,
        no_sapi: noSapi,
        peternak,
        alamat,
        provinsi,
        kota,
        kecamatan,
        kelurahan,
        bangsa,
        tgl_lahir: tglLahir,
        birahi_1: tglBirahi,
        ib_1: tglIb,
        straw,
        foto_pemilik: fotoPemilik,
        foto_sapi: fotoSapi
      });

      if (response.status) {
        // Success: refresh and go to sapi list
        await Promise.all([refreshSapiList(), refreshDashboard()]);
        navigateTo('sapi-list');
      } else {
        setError(response.msg || 'Gagal menyimpan data sapi baru.');
      }
    } catch (e: any) {
      setError(e.response?.data?.msg || 'Gagal menghubungi server backend.');
    } finally {
      setLoading(false);
    }
  };

  const breeds = dashboardData?.bangsa || [];

  return (
    <div className="app-viewport animate-fade-in" style={{ paddingBottom: '40px' }}>
      {/* Header */}
      <div style={{ display: 'flex', alignItems: 'center', marginBottom: '24px', gap: '12px' }}>
        <button
          onClick={() => navigateTo('sapi-list')}
          style={{
            background: 'rgba(255, 255, 255, 0.05)',
            border: '1px solid rgba(255, 255, 255, 0.08)',
            color: 'var(--text-main)',
            borderRadius: '50%',
            width: '40px',
            height: '40px',
            display: 'flex',
            alignItems: 'center',
            justifyContent: 'center',
            cursor: 'pointer'
          }}
        >
          <ArrowLeft size={18} />
        </button>
        <div>
          <h1 className="header-title" style={{ fontSize: '20px' }}>Daftar Sapi Baru</h1>
          <p className="subheader">Langkah {step} dari 4</p>
        </div>
      </div>

      {/* Step Indicators */}
      <div style={{ display: 'flex', gap: '8px', marginBottom: '24px' }}>
        {[1, 2, 3, 4].map((i) => (
          <div
            key={i}
            style={{
              flex: 1,
              height: '4px',
              borderRadius: '2px',
              background: i <= step ? 'var(--primary)' : 'rgba(255, 255, 255, 0.1)',
              transition: 'var(--transition)'
            }}
          ></div>
        ))}
      </div>

      {error && (
        <div className="alert alert-danger animate-fade-in">
          <ShieldAlert size={18} style={{ flexShrink: 0 }} />
          <span>{error}</span>
        </div>
      )}

      <form onSubmit={handleSubmit} className="card card-glow" style={{ padding: '20px' }}>
        
        {/* STEP 1: Peternak & Lokasi */}
        {step === 1 && (
          <div className="animate-fade-in">
            <h3 style={{ fontSize: '15px', fontWeight: '600', marginBottom: '16px', display: 'flex', alignItems: 'center', gap: '8px', color: 'var(--primary)' }}>
              <MapPin size={16} /> Data Kepemilikan & Lokasi
            </h3>

            <div className="form-group">
              <label className="form-label">Nomor Tag Sapi *</label>
              <input
                type="text"
                className="form-control"
                placeholder="Contoh: SP-099"
                value={noSapi}
                onChange={(e) => setNoSapi(e.target.value)}
                required
              />
            </div>

            <div className="form-group">
              <label className="form-label">Nama Peternak (Pemilik) *</label>
              <input
                type="text"
                className="form-control"
                placeholder="Contoh: Pak Slamet"
                value={peternak}
                onChange={(e) => setPeternak(e.target.value)}
                required
              />
            </div>

            <div className="form-group">
              <label className="form-label">Alamat Peternak *</label>
              <textarea
                className="form-control"
                placeholder="Tuliskan nama dusun/alamat lengkap..."
                value={alamat}
                onChange={(e) => setAlamat(e.target.value)}
                style={{ height: '80px', padding: '12px 16px', resize: 'none' }}
                required
              />
            </div>

            <div className="grid-2">
              <div className="form-group">
                <label className="form-label">Kecamatan</label>
                <select
                  className="form-control"
                  value={kecamatan}
                  onChange={(e) => setKecamatan(e.target.value)}
                >
                  <option value="Pemalang">Pemalang</option>
                  <option value="Taman">Taman</option>
                  <option value="Petarukan">Petarukan</option>
                  <option value="Randudongkal">Randudongkal</option>
                </select>
              </div>

              <div className="form-group">
                <label className="form-label">Kelurahan</label>
                <select
                  className="form-control"
                  value={kelurahan}
                  onChange={(e) => setKelurahan(e.target.value)}
                >
                  <option value="Bojongbata">Bojongbata</option>
                  <option value="Bojongnangka">Bojongnangka</option>
                  <option value="Mulyoharjo">Mulyoharjo</option>
                  <option value="Kebondalem">Kebondalem</option>
                </select>
              </div>
            </div>
          </div>
        )}

        {/* STEP 2: Karakteristik Sapi */}
        {step === 2 && (
          <div className="animate-fade-in">
            <h3 style={{ fontSize: '15px', fontWeight: '600', marginBottom: '16px', display: 'flex', alignItems: 'center', gap: '8px', color: 'var(--primary)' }}>
              <Tag size={16} /> Karakteristik Fisik Sapi
            </h3>

            <div className="form-group">
              <label className="form-label">Bangsa Sapi *</label>
              <select
                className="form-control"
                value={bangsa}
                onChange={(e) => setBangsa(e.target.value)}
                required
              >
                <option value="">-- Pilih Bangsa Sapi --</option>
                {breeds.map((b) => (
                  <option key={b.id_bangsa} value={b.nama_bangsa}>
                    {b.nama_bangsa}
                  </option>
                ))}
                {breeds.length === 0 && (
                  <>
                    <option value="Limousin">Limousin</option>
                    <option value="Simental">Simental</option>
                    <option value="PO (Peranakan Ongole)">PO (Peranakan Ongole)</option>
                    <option value="Brahman">Brahman</option>
                  </>
                )}
              </select>
            </div>

            <div className="form-group">
              <label className="form-label">Tanggal Lahir Sapi *</label>
              <input
                type="date"
                className="form-control"
                value={tglLahir}
                onChange={(e) => setTglLahir(e.target.value)}
                required
              />
            </div>
          </div>
        )}

        {/* STEP 3: Riwayat IB Pertama */}
        {step === 3 && (
          <div className="animate-fade-in">
            <h3 style={{ fontSize: '15px', fontWeight: '600', marginBottom: '16px', display: 'flex', alignItems: 'center', gap: '8px', color: 'var(--primary)' }}>
              <Calendar size={16} /> Pencatatan Inseminasi Buatan (IB-1)
            </h3>
            <p style={{ fontSize: '12px', color: 'var(--text-muted)', marginBottom: '16px', lineHeight: '1.4' }}>
              Daftarkan riwayat deteksi birahi dan penyuntikan semen (IB) pertama pada sapi ini.
            </p>

            <div className="form-group">
              <label className="form-label">Tanggal Terdeteksi Birahi *</label>
              <input
                type="date"
                className="form-control"
                value={tglBirahi}
                onChange={(e) => setTglBirahi(e.target.value)}
                required
              />
            </div>

            <div className="form-group">
              <label className="form-label">Tanggal Penyuntikan IB *</label>
              <input
                type="date"
                className="form-control"
                value={tglIb}
                onChange={(e) => setTglIb(e.target.value)}
                required
              />
            </div>

            <div className="form-group">
              <label className="form-label">Nomor Straw / Kode Semen Pejantan *</label>
              <input
                type="text"
                className="form-control"
                placeholder="Contoh: LIM-90221"
                value={straw}
                onChange={(e) => setStraw(e.target.value)}
                required
              />
            </div>
          </div>
        )}

        {/* STEP 4: Unggah Foto */}
        {step === 4 && (
          <div className="animate-fade-in">
            <h3 style={{ fontSize: '15px', fontWeight: '600', marginBottom: '16px', display: 'flex', alignItems: 'center', gap: '8px', color: 'var(--primary)' }}>
              <Camera size={16} /> Lampiran Foto Dokumentasi
            </h3>

            {/* Foto Sapi */}
            <div className="form-group" style={{ marginBottom: '24px' }}>
              <label className="form-label">Foto Sapi (Opsional)</label>
              <div style={{ display: 'flex', gap: '16px', alignItems: 'center' }}>
                <div style={{
                  width: '120px',
                  height: '90px',
                  borderRadius: '8px',
                  border: '1px dashed rgba(255,255,255,0.15)',
                  background: fotoSapiPreview ? `url(${fotoSapiPreview}) center/cover no-repeat` : 'rgba(16, 22, 31, 0.4)',
                  display: 'flex',
                  alignItems: 'center',
                  justifyContent: 'center',
                  overflow: 'hidden',
                  flexShrink: 0
                }}>
                  {!fotoSapiPreview && <span style={{ fontSize: '20px' }}>🐄</span>}
                </div>
                <label className="btn btn-secondary" style={{ height: '40px', width: 'auto', padding: '0 16px', fontSize: '13px' }}>
                  <Camera size={14} /> Pilih Foto
                  <input
                    type="file"
                    accept="image/*"
                    onChange={(e) => handleFileChange(e, 'sapi')}
                    style={{ display: 'none' }}
                  />
                </label>
              </div>
            </div>

            {/* Foto Pemilik */}
            <div className="form-group">
              <label className="form-label">Foto Peternak / Pemilik (Opsional)</label>
              <div style={{ display: 'flex', gap: '16px', alignItems: 'center' }}>
                <div style={{
                  width: '120px',
                  height: '90px',
                  borderRadius: '8px',
                  border: '1px dashed rgba(255,255,255,0.15)',
                  background: fotoPemilikPreview ? `url(${fotoPemilikPreview}) center/cover no-repeat` : 'rgba(16, 22, 31, 0.4)',
                  display: 'flex',
                  alignItems: 'center',
                  justifyContent: 'center',
                  overflow: 'hidden',
                  flexShrink: 0
                }}>
                  {!fotoPemilikPreview && <span style={{ fontSize: '20px' }}>👤</span>}
                </div>
                <label className="btn btn-secondary" style={{ height: '40px', width: 'auto', padding: '0 16px', fontSize: '13px' }}>
                  <Camera size={14} /> Pilih Foto
                  <input
                    type="file"
                    accept="image/*"
                    onChange={(e) => handleFileChange(e, 'pemilik')}
                    style={{ display: 'none' }}
                  />
                </label>
              </div>
            </div>
          </div>
        )}

        {/* Action Buttons Nav bar */}
        <div style={{ display: 'flex', gap: '12px', marginTop: '32px' }}>
          {step > 1 && (
            <button
              type="button"
              className="btn btn-secondary"
              onClick={handleBack}
              disabled={loading}
              style={{ flex: 1 }}
            >
              <ArrowLeft size={16} /> Kembali
            </button>
          )}
          
          {step < 4 ? (
            <button
              type="button"
              className="btn btn-primary"
              onClick={handleNext}
              style={{ flex: 2 }}
            >
              Lanjutkan <ArrowRight size={16} />
            </button>
          ) : (
            <button
              type="submit"
              className="btn btn-primary"
              disabled={loading}
              style={{ flex: 2 }}
            >
              {loading ? (
                <span style={{ display: 'flex', alignItems: 'center', gap: '8px' }}>
                  <span className="shimmer" style={{ width: '16px', height: '16px', borderRadius: '50%' }}></span>
                  Menyimpan...
                </span>
              ) : (
                <span style={{ display: 'flex', alignItems: 'center', gap: '8px' }}>
                  <Save size={16} /> Simpan Data Sapi
                </span>
              )}
            </button>
          )}
        </div>
      </form>
    </div>
  );
};
