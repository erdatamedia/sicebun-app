import React, { useEffect } from 'react';
import { useApp } from '../context/AppContext';
import { Bell, AlertTriangle, ArrowRight, Clock, Tag } from 'lucide-react';


export const AlarmView: React.FC = () => {
  const { user, alarmList, loading, refreshAlarms, viewSapiDetail } = useApp();

  useEffect(() => {
    if (user) {
      refreshAlarms();
    }
  }, [user]);

  // Compute what alarm text to show if details are missing from database
  const getAlarmText = (sapi: any) => {
    if (sapi.desc || sapi.title) {
      return {
        title: sapi.title || 'Pemberitahuan Kesehatan',
        desc: sapi.desc || `Sapi tag #${sapi.no_sapi} membutuhkan peninjauan berkala.`
      };
    }

    // Default calculations if backend sends raw sapi entries
    if (sapi.status === 'ib1' || sapi.status === 'ib2' || sapi.status === 'ib3') {
      return {
        title: 'Cek Kebuntingan (PK)',
        desc: `Sapi Tag #${sapi.no_sapi} sudah memasuki masa pasca inseminasi. Harap lakukan pemeriksaan kebuntingan (USG/Palpasi Rektal) secara berkala.`
      };
    } else if (sapi.status === 'bunting') {
      return {
        title: 'Persiapan Melahirkan (Calving)',
        desc: `Masa gestasi Sapi Tag #${sapi.no_sapi} mendekati usia melahirkan. Harap persiapkan lingkungan kandang bersih dan kering.`
      };
    } else if (sapi.status === 'gangrep') {
      return {
        title: 'Penanganan Gangguan Repro',
        desc: `Sapi Tag #${sapi.no_sapi} terindikasi mengalami gangguan reproduksi. Segera konsultasikan dengan dokter hewan pendamping.`
      };
    }

    return {
      title: 'Pemeriksaan Rutin',
      desc: `Lakukan monitoring kesehatan reproduksi berkala untuk Sapi Tag #${sapi.no_sapi}.`
    };
  };

  return (
    <div className="app-viewport animate-fade-in" style={{ paddingBottom: '100px' }}>
      {/* Header */}
      <div style={{ marginBottom: '24px' }}>
        <h1 className="header-title" style={{ fontSize: '20px', display: 'flex', alignItems: 'center', gap: '10px' }}>
          <Bell size={20} style={{ color: 'var(--primary)' }} /> Alarm & Peringatan
        </h1>
        <p className="subheader">Jadwal pemeriksaan inseminasi buatan dan deteksi kebuntingan penting</p>
      </div>

      {loading ? (
        <div style={{ display: 'flex', flexDirection: 'column', gap: '12px' }}>
          <div className="card shimmer" style={{ height: '90px' }}></div>
          <div className="card shimmer" style={{ height: '90px' }}></div>
          <div className="card shimmer" style={{ height: '90px' }}></div>
        </div>
      ) : alarmList && alarmList.length > 0 ? (
        <div style={{ display: 'flex', flexDirection: 'column', gap: '12px' }}>
          {alarmList.map((sapi) => {
            const alarmInfo = getAlarmText(sapi);
            const isDanger = sapi.status === 'gangrep';
            const isSuccess = sapi.status === 'bunting';

            return (
              <div
                key={sapi.id_sapi}
                className="card card-glow"
                onClick={() => viewSapiDetail(sapi.id_sapi)}
                style={{
                  padding: '18px',
                  borderLeft: '4px solid',
                  borderLeftColor: isDanger ? 'var(--danger)' : isSuccess ? 'var(--success)' : 'var(--warning)',
                  cursor: 'pointer',
                  display: 'flex',
                  gap: '14px',
                  alignItems: 'flex-start',
                  transition: 'var(--transition)'
                }}
              >
                {/* Warning icon */}
                <div style={{
                  background: isDanger ? 'rgba(239, 68, 68, 0.1)' : isSuccess ? 'rgba(16, 185, 129, 0.1)' : 'rgba(245, 158, 11, 0.1)',
                  color: isDanger ? 'var(--danger)' : isSuccess ? 'var(--success)' : 'var(--warning)',
                  padding: '10px',
                  borderRadius: '12px',
                  display: 'flex',
                  alignItems: 'center',
                  justifyContent: 'center',
                  flexShrink: 0
                }}>
                  <AlertTriangle size={18} />
                </div>

                <div style={{ flex: 1, minWidth: 0 }}>
                  <div style={{ display: 'flex', alignItems: 'center', justifyContent: 'space-between', gap: '8px' }}>
                    <h4 style={{ fontSize: '14px', fontWeight: '700', color: 'var(--text-main)', display: 'flex', alignItems: 'center', gap: '6px' }}>
                      {alarmInfo.title}
                    </h4>
                    <span style={{ fontSize: '10px', color: 'var(--text-muted)', display: 'flex', alignItems: 'center', gap: '4px' }}>
                      <Clock size={10} /> Aktif
                    </span>
                  </div>

                  <p style={{ fontSize: '12px', color: 'var(--text-sub)', marginTop: '6px', lineHeight: '1.5' }}>
                    {alarmInfo.desc}
                  </p>

                  <div style={{ display: 'flex', alignItems: 'center', justifyContent: 'space-between', marginTop: '12px', paddingTop: '10px', borderTop: '1px solid rgba(255,255,255,0.03)' }}>
                    <span style={{ fontSize: '11px', color: 'var(--text-muted)', display: 'flex', alignItems: 'center', gap: '4px' }}>
                      <Tag size={11} style={{ color: 'var(--primary)' }} /> Tag #{sapi.no_sapi}
                    </span>
                    <span style={{ fontSize: '11px', color: 'var(--primary)', fontWeight: '600', display: 'flex', alignItems: 'center', gap: '3px' }}>
                      Lihat Rekam <ArrowRight size={12} />
                    </span>
                  </div>
                </div>
              </div>
            );
          })}
        </div>
      ) : (
        <div className="card" style={{ padding: '40px 20px', textAlign: 'center', color: 'var(--text-muted)' }}>
          <div style={{
            display: 'inline-flex',
            padding: '12px',
            borderRadius: '50%',
            background: 'rgba(255,255,255,0.02)',
            marginBottom: '12px'
          }}>
            <Bell size={32} style={{ color: 'var(--text-muted)' }} />
          </div>
          <h4 style={{ fontSize: '14px', color: 'var(--text-main)', fontWeight: '600' }}>Semua Terpantau Aman</h4>
          <p style={{ fontSize: '12px', color: 'var(--text-sub)', marginTop: '4px' }}>
            Saat ini tidak ada alarm atau jadwal pemeriksaan terlewat untuk sapi binaan Anda.
          </p>
        </div>
      )}
    </div>
  );
};
