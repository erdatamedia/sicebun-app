import React, { useEffect, useState } from 'react';
import { useApp } from '../context/AppContext';
import { sapiService } from '../services/api';
import { ShimmerDetail } from '../components/Shimmer';
import { ArrowLeft, Edit3, User, MapPin, AlertTriangle, CheckCircle, X, Sparkles, BookOpen } from 'lucide-react';
import type { Sapi } from '../types/api';


const resolvePhoto = (photoPath?: string, fallback: string = 'https://images.unsplash.com/photo-1546445317-29f4545e6d49?w=300') => {
  if (!photoPath) return fallback;
  if (photoPath.startsWith('http') || photoPath.startsWith('data:')) return photoPath;
  const clean = photoPath.replace(/^\.\/assets\/uploads\//, '').replace(/^assets\/uploads\//, '');
  return `/assets/uploads/${clean}`;
};

export const SapiDetailView: React.FC = () => {
  const { sapiDetailId, navigateTo, refreshSapiList, refreshDashboard } = useApp();
  const [sapi, setSapi] = useState<Sapi | null>(null);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState<string | null>(null);

  // Modal State
  const [showUpdateModal, setShowUpdateModal] = useState(false);
  const [newStatus, setNewStatus] = useState<Sapi['status']>('ib1');
  
  // Modal Form inputs
  const [modalTglBirahi, setModalTglBirahi] = useState('');
  const [modalTglIb, setModalTglIb] = useState('');
  const [modalStraw, setModalStraw] = useState('');
  const [modalKet, setModalKet] = useState('');
  const [modalTglMelahirkan, setModalTglMelahirkan] = useState('');
  const [submitting, setSubmitting] = useState(false);

  const fetchDetail = async () => {
    if (!sapiDetailId) return;
    setLoading(true);
    setError(null);
    try {
      const response = await sapiService.getSapiDetail(sapiDetailId);
      if (response.status && response.data) {
        setSapi(response.data);
        setNewStatus(response.data.status);
      } else {
        setError(response.msg || 'Gagal memuat rincian sapi.');
      }
    } catch (e: any) {
      setError(e.response?.data?.msg || 'Koneksi ke backend gagal.');
    } finally {
      setLoading(false);
    }
  };

  useEffect(() => {
    fetchDetail();
  }, [sapiDetailId]);

  // Gestation countdown calculations
  const getGestationCountdown = () => {
    if (!sapi) return null;
    
    // Check if cow is confirmed pregnant
    const isPregnant = sapi.status === 'bunting' || sapi.is_bunting === '1';
    if (!isPregnant) return null;

    // Use prediction date or calculate roughly (IB date + 283 days)
    let predDate: Date;
    if (sapi.prediksi_tgl_lahir) {
      predDate = new Date(sapi.prediksi_tgl_lahir);
    } else {
      // Find latest IB date
      const latestIbStr = sapi.ib_3 || sapi.ib_2 || sapi.ib_1;
      if (!latestIbStr) return null;
      const ibDate = new Date(latestIbStr);
      predDate = new Date(ibDate.setDate(ibDate.getDate() + 283));
    }

    const today = new Date();
    const diffTime = predDate.getTime() - today.getTime();
    const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
    
    // Gestation age calculation
    let elapsedDays = 0;
    const latestIbStr = sapi.ib_3 || sapi.ib_2 || sapi.ib_1;
    if (latestIbStr) {
      const ibDate = new Date(latestIbStr);
      const elapsedMs = today.getTime() - ibDate.getTime();
      elapsedDays = Math.floor(elapsedMs / (1000 * 60 * 60 * 24));
    }

    const totalPregnancyDays = 283;
    const progressPercent = Math.min(100, Math.max(0, Math.floor((elapsedDays / totalPregnancyDays) * 100)));

    return {
      remainingDays: diffDays,
      elapsedDays,
      months: Math.floor(elapsedDays / 30),
      daysRemainder: elapsedDays % 30,
      progressPercent,
      predictionStr: predDate.toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' })
    };
  };

  const handleUpdateStatus = async (e: React.FormEvent) => {
    e.preventDefault();
    if (!sapi) return;
    setSubmitting(true);
    setError(null);

    try {
      const response = await sapiService.updateSapiStatus({
        id_sapi: sapi.id_sapi,
        status: newStatus,
        tgl_birahi: modalTglBirahi || undefined,
        tgl_ib: modalTglIb || undefined,
        ket: modalKet || undefined,
        straw: modalStraw || undefined,
        tgl_melahirkan: modalTglMelahirkan || undefined
      });

      if (response.status) {
        setShowUpdateModal(false);
        // Reset form
        setModalTglBirahi('');
        setModalTglIb('');
        setModalStraw('');
        setModalKet('');
        setModalTglMelahirkan('');
        
        // Refresh details, list, and dashboard
        await Promise.all([fetchDetail(), refreshSapiList(), refreshDashboard()]);
      } else {
        setError(response.msg || 'Gagal memperbarui status sapi.');
      }
    } catch (e: any) {
      setError(e.response?.data?.msg || 'Gagal menghubungi server.');
    } finally {
      setSubmitting(false);
    }
  };

  const getStatusLabel = (status?: string) => {
    switch (status) {
      case 'ib1': return 'IB Ke-1';
      case 'ib2': return 'IB Ke-2';
      case 'ib3': return 'IB Ke-3';
      case 'bunting': return 'Bunting';
      case 'tidak_bunting': return 'Tidak Bunting';
      case 'gangrep': return 'Gangrep';
      case 'kelahiran': return 'Melahirkan';
      default: return status || '';
    }
  };

  if (loading) {
    return (
      <div className="app-viewport animate-fade-in">
        <ShimmerDetail />
      </div>
    );
  }

  if (error || !sapi) {
    return (
      <div className="app-viewport animate-fade-in">
        <div className="alert alert-danger" style={{ marginTop: '20px' }}>
          <AlertTriangle size={18} />
          <span>{error || 'Data sapi tidak ditemukan'}</span>
        </div>
        <button className="btn btn-secondary" onClick={() => navigateTo('sapi-list')}>
          <ArrowLeft size={16} /> Kembali ke Daftar
        </button>
      </div>
    );
  }

  const countdown = getGestationCountdown();

  return (
    <div className="app-viewport animate-fade-in" style={{ paddingBottom: '60px' }}>
      {/* Top Navigation */}
      <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center', marginBottom: '20px' }}>
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
        <span className="badge badge-ib1" style={{ textTransform: 'capitalize' }}>
          Detail Rekam Medis
        </span>
      </div>

      {/* Main Cover Cow Image Card */}
      <div className="card" style={{ padding: 0, overflow: 'hidden', position: 'relative', border: 'none', marginBottom: '24px' }}>
        <img
          src={resolvePhoto(sapi.foto_sapi)}
          alt={sapi.no_sapi}
          style={{ width: '100%', height: '220px', objectFit: 'cover' }}
          onError={(e) => {
            (e.target as HTMLImageElement).src = 'https://images.unsplash.com/photo-1546445317-29f4545e6d49?w=300';
          }}
        />
        <div style={{
          position: 'absolute',
          bottom: 0,
          left: 0,
          right: 0,
          background: 'linear-gradient(0deg, rgba(7, 9, 13, 0.95) 0%, rgba(7, 9, 13, 0) 100%)',
          padding: '20px 16px 12px 16px',
          display: 'flex',
          justifyContent: 'space-between',
          alignItems: 'flex-end'
        }}>
          <div>
            <h2 style={{ fontSize: '22px', fontWeight: '700', color: 'var(--text-main)', fontFamily: 'var(--font-title)' }}>
              Tag #{sapi.no_sapi}
            </h2>
            <span style={{ fontSize: '12px', color: 'var(--text-sub)', display: 'block', marginTop: '3px' }}>
              Bangsa Sapi: <strong style={{ color: 'var(--accent)' }}>{sapi.bangsa || 'Limousin'}</strong>
            </span>
          </div>
          <span className={`badge badge-${sapi.status}`} style={{ fontSize: '11px', padding: '6px 12px' }}>
            {getStatusLabel(sapi.status)}
          </span>
        </div>
      </div>

      {/* Gestation Countdown Widget */}
      {countdown && (
        <div className="card card-glow" style={{
          padding: '20px',
          background: 'linear-gradient(135deg, rgba(22, 31, 44, 0.95) 0%, rgba(16, 22, 31, 0.95) 100%)',
          marginBottom: '24px',
          border: '1px solid hsla(140, 80%, 40%, 0.15)'
        }}>
          <h3 style={{ fontSize: '14px', fontWeight: '600', display: 'flex', alignItems: 'center', gap: '8px', color: '#4ade80' }}>
            <Sparkles size={16} /> Kalkulator Kebuntingan
          </h3>

          <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center', marginTop: '16px', marginBottom: '8px' }}>
            <div>
              <span style={{ fontSize: '28px', fontWeight: '700', color: 'var(--text-main)' }}>
                {countdown.remainingDays > 0 ? countdown.remainingDays : '0'}
              </span>
              <span style={{ fontSize: '13px', color: 'var(--text-sub)', marginLeft: '6px' }}>Hari Menuju Calving</span>
            </div>
            <div style={{ textAlign: 'right' }}>
              <span style={{ fontSize: '13px', color: 'var(--text-sub)', display: 'block' }}>Umur Kebuntingan</span>
              <span style={{ fontSize: '14px', fontWeight: '600', color: 'var(--text-main)', marginTop: '2px', display: 'block' }}>
                {countdown.months} Bulan {countdown.daysRemainder} Hari
              </span>
            </div>
          </div>

          {/* Progress Indicator Bar */}
          <div style={{ width: '100%', height: '8px', background: 'rgba(255,255,255,0.06)', borderRadius: '4px', overflow: 'hidden', marginTop: '12px' }}>
            <div style={{
              width: `${countdown.progressPercent}%`,
              height: '100%',
              background: 'linear-gradient(90deg, #10b981 0%, #34d399 100%)',
              borderRadius: '4px',
              transition: 'width 0.5s ease'
            }}></div>
          </div>

          <div style={{ display: 'flex', justifyContent: 'space-between', fontSize: '11px', color: 'var(--text-muted)', marginTop: '8px' }}>
            <span>IB Terakhir: {sapi.ib_3 || sapi.ib_2 || sapi.ib_1}</span>
            <span>Prediksi Kelahiran: {countdown.predictionStr}</span>
          </div>
        </div>
      )}

      {/* Owner & Location Card */}
      <div className="card" style={{ padding: '16px' }}>
        <h3 style={{ fontSize: '14px', fontWeight: '600', marginBottom: '12px', color: 'var(--text-main)', display: 'flex', alignItems: 'center', gap: '8px' }}>
          <User size={15} style={{ color: 'var(--primary)' }} /> Peternak & Lokasi Binaan
        </h3>
        
        <div style={{ display: 'flex', alignItems: 'center', gap: '16px' }}>
          <img
            src={resolvePhoto(sapi.foto_pemilik, 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=150')}
            alt={sapi.peternak}
            style={{ width: '48px', height: '48px', borderRadius: '50%', objectFit: 'cover', border: '1px solid rgba(255,255,255,0.08)' }}
            onError={(e) => {
              (e.target as HTMLImageElement).src = 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=150';
            }}
          />
          <div>
            <h4 style={{ fontSize: '14px', fontWeight: '600', color: 'var(--text-main)' }}>{sapi.peternak}</h4>
            <p style={{ fontSize: '11px', color: 'var(--text-sub)', marginTop: '4px', display: 'flex', alignItems: 'center', gap: '6px' }}>
              <MapPin size={11} /> {sapi.alamat}, Kec. {sapi.kecamatan || 'Pemalang'}, Kel. {sapi.kelurahan || 'Bojongbata'}
            </p>
          </div>
        </div>
      </div>

      {/* Sapi Breeding History Timeline */}
      <div className="card" style={{ padding: '20px' }}>
        <h3 style={{ fontSize: '14px', fontWeight: '600', marginBottom: '20px', color: 'var(--text-main)', display: 'flex', alignItems: 'center', gap: '8px' }}>
          <BookOpen size={15} style={{ color: 'var(--accent)' }} /> Linimasa Siklus Reproduksi
        </h3>

        <div style={{ display: 'flex', flexDirection: 'column', gap: '20px', position: 'relative' }}>
          {/* Vertical line decorator */}
          <div style={{
            position: 'absolute',
            left: '11px',
            top: '8px',
            bottom: '8px',
            width: '2px',
            background: 'rgba(255,255,255,0.06)'
          }}></div>

          {/* Birth Event */}
          <div style={{ display: 'flex', gap: '16px', position: 'relative', zIndex: 2 }}>
            <div style={{
              width: '24px',
              height: '24px',
              borderRadius: '50%',
              background: '#1e293b',
              border: '2px solid var(--primary)',
              display: 'flex',
              alignItems: 'center',
              justifyContent: 'center',
              flexShrink: 0
            }}>
              <div style={{ width: '8px', height: '8px', borderRadius: '50%', background: 'var(--primary)' }}></div>
            </div>
            <div>
              <h4 style={{ fontSize: '13px', fontWeight: '600', color: 'var(--text-main)' }}>Registrasi Lahir Sapi</h4>
              <span style={{ fontSize: '11px', color: 'var(--text-muted)' }}>Tanggal Lahir: {sapi.tgl_lahir ? new Date(sapi.tgl_lahir).toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' }) : '-'}</span>
            </div>
          </div>

          {/* IB-1 Event */}
          {sapi.ib_1 && (
            <div style={{ display: 'flex', gap: '16px', position: 'relative', zIndex: 2 }}>
              <div style={{
                width: '24px',
                height: '24px',
                borderRadius: '50%',
                background: '#1e293b',
                border: '2px solid var(--primary)',
                display: 'flex',
                alignItems: 'center',
                justifyContent: 'center',
                flexShrink: 0
              }}>
                <div style={{ width: '8px', height: '8px', borderRadius: '50%', background: 'var(--primary)' }}></div>
              </div>
              <div>
                <h4 style={{ fontSize: '13px', fontWeight: '600', color: 'var(--text-main)' }}>Inseminasi Buatan Ke-1</h4>
                <div style={{ display: 'flex', flexDirection: 'column', gap: '3px', marginTop: '3px', fontSize: '11px', color: 'var(--text-sub)' }}>
                  <span>Birahi Terpantau: {sapi.birahi_1 || '-'}</span>
                  <span>Tanggal Penyuntikan IB: {sapi.ib_1}</span>
                  <span>Nomor Straw Semen: <strong style={{ color: 'var(--accent)' }}>{sapi.straw_1 || '-'}</strong></span>
                  {sapi.ket_1 && <span>Keterangan: {sapi.ket_1}</span>}
                </div>
              </div>
            </div>
          )}

          {/* IB-2 Event */}
          {sapi.ib_2 && (
            <div style={{ display: 'flex', gap: '16px', position: 'relative', zIndex: 2 }}>
              <div style={{
                width: '24px',
                height: '24px',
                borderRadius: '50%',
                background: '#1e293b',
                border: '2px solid var(--primary)',
                display: 'flex',
                alignItems: 'center',
                justifyContent: 'center',
                flexShrink: 0
              }}>
                <div style={{ width: '8px', height: '8px', borderRadius: '50%', background: 'var(--primary)' }}></div>
              </div>
              <div>
                <h4 style={{ fontSize: '13px', fontWeight: '600', color: 'var(--text-main)' }}>Inseminasi Buatan Ke-2</h4>
                <div style={{ display: 'flex', flexDirection: 'column', gap: '3px', marginTop: '3px', fontSize: '11px', color: 'var(--text-sub)' }}>
                  <span>Birahi Terpantau: {sapi.birahi_2 || '-'}</span>
                  <span>Tanggal Penyuntikan IB: {sapi.ib_2}</span>
                  <span>Nomor Straw Semen: <strong style={{ color: 'var(--accent)' }}>{sapi.straw_2 || '-'}</strong></span>
                  {sapi.ket_2 && <span>Keterangan: {sapi.ket_2}</span>}
                </div>
              </div>
            </div>
          )}

          {/* IB-3 Event */}
          {sapi.ib_3 && (
            <div style={{ display: 'flex', gap: '16px', position: 'relative', zIndex: 2 }}>
              <div style={{
                width: '24px',
                height: '24px',
                borderRadius: '50%',
                background: '#1e293b',
                border: '2px solid var(--primary)',
                display: 'flex',
                alignItems: 'center',
                justifyContent: 'center',
                flexShrink: 0
              }}>
                <div style={{ width: '8px', height: '8px', borderRadius: '50%', background: 'var(--primary)' }}></div>
              </div>
              <div>
                <h4 style={{ fontSize: '13px', fontWeight: '600', color: 'var(--text-main)' }}>Inseminasi Buatan Ke-3</h4>
                <div style={{ display: 'flex', flexDirection: 'column', gap: '3px', marginTop: '3px', fontSize: '11px', color: 'var(--text-sub)' }}>
                  <span>Birahi Terpantau: {sapi.birahi_3 || '-'}</span>
                  <span>Tanggal Penyuntikan IB: {sapi.ib_3}</span>
                  <span>Nomor Straw Semen: <strong style={{ color: 'var(--accent)' }}>{sapi.straw_3 || '-'}</strong></span>
                  {sapi.ket_3 && <span>Keterangan: {sapi.ket_3}</span>}
                </div>
              </div>
            </div>
          )}

          {/* Gestation Confirmed Event */}
          {(sapi.status === 'bunting' || sapi.is_bunting === '1') && (
            <div style={{ display: 'flex', gap: '16px', position: 'relative', zIndex: 2 }}>
              <div style={{
                width: '24px',
                height: '24px',
                borderRadius: '50%',
                background: '#064e3b',
                border: '2px solid #10b981',
                display: 'flex',
                alignItems: 'center',
                justifyContent: 'center',
                flexShrink: 0
              }}>
                <CheckCircle size={12} style={{ color: '#10b981' }} />
              </div>
              <div>
                <h4 style={{ fontSize: '13px', fontWeight: '600', color: '#34d399' }}>Kebuntingan Dikonfirmasi</h4>
                <span style={{ fontSize: '11px', color: 'var(--text-sub)' }}>Sapi dinyatakan bunting ({sapi.umur_kebuntingan || '9'} Bulan)</span>
              </div>
            </div>
          )}

          {/* Calved Event */}
          {(sapi.status === 'kelahiran' || sapi.is_melahirkan === '1') && (
            <div style={{ display: 'flex', gap: '16px', position: 'relative', zIndex: 2 }}>
              <div style={{
                width: '24px',
                height: '24px',
                borderRadius: '50%',
                background: 'rgba(244,114,182,0.15)',
                border: '2px solid #f472b6',
                display: 'flex',
                alignItems: 'center',
                justifyContent: 'center',
                flexShrink: 0
              }}>
                <Sparkles size={12} style={{ color: '#f472b6' }} />
              </div>
              <div>
                <h4 style={{ fontSize: '13px', fontWeight: '600', color: '#f472b6' }}>Kelahiran Pedet</h4>
                <div style={{ display: 'flex', flexDirection: 'column', gap: '3px', marginTop: '3px', fontSize: '11px', color: 'var(--text-sub)' }}>
                  <span>Tanggal Lahir Pedet: {sapi.tgl_melahirkan || '-'}</span>
                  <span>Keterangan Kelahiran: {sapi.kelahiran || 'Lahir Sehat'}</span>
                </div>
              </div>
            </div>
          )}
        </div>
      </div>

      {/* Update Lifecycle Status Button */}
      <button className="btn btn-primary" onClick={() => setShowUpdateModal(true)} style={{ marginTop: '24px' }}>
        <Edit3 size={16} /> Perbarui Siklus Reproduksi Sapi
      </button>

      {/* UPDATE STATUS WIZARD MODAL DRAWER */}
      {showUpdateModal && (
        <div style={{
          position: 'absolute',
          top: 0,
          left: 0,
          right: 0,
          bottom: 0,
          background: 'rgba(7, 9, 13, 0.85)',
          backdropFilter: 'blur(10px)',
          WebkitBackdropFilter: 'blur(10px)',
          zIndex: 200,
          display: 'flex',
          justifyContent: 'center',
          alignItems: 'flex-end'
        }} className="animate-fade-in">
          <form onSubmit={handleUpdateStatus} className="card card-glow animate-fade-in" style={{
            width: '100%',
            maxWidth: '450px',
            borderBottomLeftRadius: 0,
            borderBottomRightRadius: 0,
            borderTopLeftRadius: '24px',
            borderTopRightRadius: '24px',
            margin: 0,
            padding: '24px 20px',
            maxHeight: '90%',
            overflowY: 'auto',
            background: 'var(--bg-gradient)'
          }}>
            <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center', marginBottom: '20px' }}>
              <div>
                <h3 className="header-title" style={{ fontSize: '16px' }}>Perbarui Siklus</h3>
                <p className="subheader">Input update rekam reproduksi Sapi #{sapi.no_sapi}</p>
              </div>
              <button
                type="button"
                onClick={() => setShowUpdateModal(false)}
                style={{
                  background: 'rgba(255, 255, 255, 0.05)',
                  border: 'none',
                  color: 'var(--text-main)',
                  width: '32px',
                  height: '32px',
                  borderRadius: '50%',
                  cursor: 'pointer',
                  display: 'flex',
                  alignItems: 'center',
                  justifyContent: 'center'
                }}
              >
                <X size={16} />
              </button>
            </div>

            <div className="form-group">
              <label className="form-label">Siklus Keadaan Terkini *</label>
              <select
                className="form-control"
                value={newStatus}
                onChange={(e) => setNewStatus(e.target.value as Sapi['status'])}
                required
              >
                <option value="ib1">Inseminasi Buatan (IB-1)</option>
                <option value="ib2">Inseminasi Buatan (IB-2)</option>
                <option value="ib3">Inseminasi Buatan (IB-3)</option>
                <option value="bunting">Nyatakan Bunting (Pregnancy)</option>
                <option value="tidak_bunting">Batal Bunting (Tidak Bunting)</option>
                <option value="gangrep">Gangguan Reproduksi (Gangrep)</option>
                <option value="kelahiran">Konfirmasi Kelahiran Pedet (Calving)</option>
              </select>
            </div>

            {/* CONDITIONAL SUB-FORM: IB (ib1, ib2, ib3) */}
            {(newStatus === 'ib1' || newStatus === 'ib2' || newStatus === 'ib3') && (
              <div className="animate-fade-in" style={{ background: 'rgba(255,255,255,0.02)', padding: '14px', borderRadius: '12px', border: '1px solid rgba(255,255,255,0.05)', marginBottom: '20px' }}>
                <h4 style={{ fontSize: '12px', fontWeight: '600', color: 'var(--primary)', marginBottom: '12px' }}>Pencatatan Semen Pejantan (Straw)</h4>
                
                <div className="form-group">
                  <label className="form-label">Tanggal Deteksi Birahi *</label>
                  <input
                    type="date"
                    className="form-control"
                    value={modalTglBirahi}
                    onChange={(e) => setModalTglBirahi(e.target.value)}
                    required
                  />
                </div>

                <div className="form-group">
                  <label className="form-label">Tanggal Penyuntikan IB *</label>
                  <input
                    type="date"
                    className="form-control"
                    value={modalTglIb}
                    onChange={(e) => setModalTglIb(e.target.value)}
                    required
                  />
                </div>

                <div className="form-group">
                  <label className="form-label">Kode Semen Straw Pejantan *</label>
                  <input
                    type="text"
                    className="form-control"
                    placeholder="Contoh: SMT-98121"
                    value={modalStraw}
                    onChange={(e) => setModalStraw(e.target.value)}
                    required
                  />
                </div>
              </div>
            )}

            {/* CONDITIONAL SUB-FORM: Kelahiran / Calving */}
            {newStatus === 'kelahiran' && (
              <div className="animate-fade-in" style={{ background: 'rgba(255,255,255,0.02)', padding: '14px', borderRadius: '12px', border: '1px solid rgba(255,255,255,0.05)', marginBottom: '20px' }}>
                <h4 style={{ fontSize: '12px', fontWeight: '600', color: 'var(--primary)', marginBottom: '12px' }}>Konfirmasi Kelahiran Pedet</h4>
                
                <div className="form-group">
                  <label className="form-label">Tanggal Melahirkan *</label>
                  <input
                    type="date"
                    className="form-control"
                    value={modalTglMelahirkan}
                    onChange={(e) => setModalTglMelahirkan(e.target.value)}
                    required
                  />
                </div>

                <div className="form-group">
                  <label className="form-label">Status Pedet / Kelahiran *</label>
                  <input
                    type="text"
                    className="form-control"
                    placeholder="Contoh: Lahir Jantan Sehat, Berat 35kg"
                    value={modalKet}
                    onChange={(e) => setModalKet(e.target.value)}
                    required
                  />
                </div>
              </div>
            )}

            {/* Default Keterangan (always visible for non-IB/non-kelahiran states as general remarks) */}
            {newStatus !== 'ib1' && newStatus !== 'ib2' && newStatus !== 'ib3' && newStatus !== 'kelahiran' && (
              <div className="form-group">
                <label className="form-label">Catatan Pemeriksaan Tambahan (Opsional)</label>
                <textarea
                  className="form-control"
                  placeholder="Tuliskan hasil pemeriksaan USG/Palpasi Rektal..."
                  value={modalKet}
                  onChange={(e) => setModalKet(e.target.value)}
                  style={{ height: '70px', padding: '10px 14px', resize: 'none' }}
                />
              </div>
            )}

            <button type="submit" className="btn btn-primary" disabled={submitting}>
              {submitting ? (
                <span style={{ display: 'flex', alignItems: 'center', gap: '8px' }}>
                  <span className="shimmer" style={{ width: '16px', height: '16px', borderRadius: '50%' }}></span>
                  Menyimpan Rekam...
                </span>
              ) : (
                <span style={{ display: 'flex', alignItems: 'center', gap: '8px' }}>
                  <Sparkles size={16} /> Simpan Pembaruan
                </span>
              )}
            </button>
          </form>
        </div>
      )}
    </div>
  );
};
