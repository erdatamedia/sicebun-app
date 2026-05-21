import React, { useState } from 'react';
import { useApp } from '../context/AppContext';
import { ShimmerDashboard } from '../components/Shimmer';
import { Plus, User, MessageSquare, Award, BookOpen, Compass, X } from 'lucide-react';



// Path resolver for uploads
const resolvePhoto = (photoPath?: string, type: 'user' | 'sapi' | 'directory' | 'consultant' = 'user') => {
  if (!photoPath) {
    if (type === 'user') return 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=150';
    if (type === 'sapi') return 'https://images.unsplash.com/photo-1546445317-29f4545e6d49?w=300';
    if (type === 'directory') return 'https://images.unsplash.com/photo-1500937386664-56d1dfef3854?w=300';
    return 'https://images.unsplash.com/photo-1622253692010-333f2da6031d?w=150'; // consultant
  }
  
  if (photoPath.startsWith('http') || photoPath.startsWith('data:')) {
    return photoPath;
  }

  // Remove possible duplicate folder roots
  let clean = photoPath.replace(/^\.\/assets\/uploads\//, '').replace(/^assets\/uploads\//, '');
  
  // If it's a profile/user upload sometimes stored in a specific subfolder
  if (type === 'user' && !clean.startsWith('users/')) {
    clean = `users/${clean}`;
  }
  return `/assets/uploads/${clean}`;
};

export const DashboardView: React.FC = () => {
  const { user, dashboardData, loading, navigateTo } = useApp();
  const [selectedDir, setSelectedDir] = useState<any | null>(null);


  if (loading || !dashboardData) {
    return (
      <div className="app-viewport animate-fade-in">
        <ShimmerDashboard />
      </div>
    );
  }

  const { jumlah_sapi, status_sapi, direktori, bangsa, konsultan } = dashboardData;

  // Find counts of different cow statuses
  const getStatusCount = (statusKey: string) => {
    let key = statusKey;
    if (statusKey.toLowerCase() === 'bunting') key = 'b';
    if (statusKey.toLowerCase() === 'tidak_bunting') key = 'tb';
    if (statusKey.toLowerCase() === 'kelahiran') key = 'k';
    if (statusKey.toLowerCase() === 'gangrep') key = 'gr';

    if (!status_sapi || !Array.isArray(status_sapi)) return '0';

    const found = status_sapi.find(s => s && s.initial && s.initial.toLowerCase() === key.toLowerCase());
    return found ? found.jumlah : '0';
  };

  const parseCount = (countStr: string): number => {
    const matched = countStr.match(/\d+/);
    return matched ? parseInt(matched[0], 10) : 0;
  };



  return (
    <div className="app-viewport animate-fade-in" style={{ paddingBottom: '100px' }}>
      {/* 1. Welcoming User Card */}
      <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center', marginBottom: '24px' }}>
        <div>
          <span style={{ fontSize: '13px', color: 'var(--text-muted)', fontWeight: '500' }}>Selamat datang kembali,</span>
          <h2 className="header-title" style={{ fontSize: '20px', lineHeight: '1.2' }}>{user?.name}</h2>
          <span className="badge badge-ib1" style={{ fontSize: '10px', marginTop: '4px', textTransform: 'capitalize' }}>
            {user?.peran === 'inseminator' ? 'Petugas Inseminator' : user?.peran || 'Petugas'}
          </span>
        </div>
        <img
          src={resolvePhoto(user?.photo, 'user')}
          alt={user?.name}
          className="profile-avatar"
          onClick={() => navigateTo('profile')}
          style={{ cursor: 'pointer', boxShadow: '0 0 15px hsla(var(--primary-h), var(--primary-s), var(--primary-l), 0.3)' }}
          onError={(e) => {
            (e.target as HTMLImageElement).src = 'https://images.unsplash.com/photo-1535713875002-d1d0cf377fde?w=150';
          }}
        />
      </div>

      {/* 2. Hero Statistics */}
      <div className="card card-glow" style={{
        padding: '24px',
        background: 'linear-gradient(135deg, rgba(22, 31, 44, 0.9) 0%, rgba(16, 22, 31, 0.9) 100%)',
        marginBottom: '20px',
        position: 'relative',
        overflow: 'hidden'
      }}>
        <div style={{ position: 'relative', zIndex: 2 }}>
          <span style={{ fontSize: '13px', color: 'var(--text-sub)', fontWeight: '500', display: 'flex', alignItems: 'center', gap: '6px' }}>
            <Compass size={14} style={{ color: 'var(--primary)' }} /> Total Kelolaan Sapi
          </span>
          <div style={{ display: 'flex', alignItems: 'baseline', gap: '8px', marginTop: '12px' }}>
            <span style={{ fontSize: '42px', fontWeight: '700', color: 'var(--text-main)', fontFamily: 'var(--font-title)' }}>
              {jumlah_sapi || '0'}
            </span>
            <span style={{ fontSize: '14px', color: 'var(--text-muted)' }}>ekor sapi</span>
          </div>
        </div>
        {/* Glow backdrop decorator */}
        <div style={{
          position: 'absolute',
          right: '-20px',
          bottom: '-20px',
          width: '120px',
          height: '120px',
          borderRadius: '50%',
          background: 'hsla(var(--primary-h), var(--primary-s), var(--primary-l), 0.15)',
          filter: 'blur(30px)'
        }}></div>
      </div>

      {/* 3. Sub Statistics Grid */}
      <div className="grid-2" style={{ marginBottom: '24px' }}>
        <div className="card" style={{ margin: 0, padding: '16px', display: 'flex', flexDirection: 'column', gap: '4px' }}>
          <span className="badge badge-bunting" style={{ alignSelf: 'flex-start' }}>Bunting</span>
          <span style={{ fontSize: '24px', fontWeight: '700', color: 'var(--text-main)' }}>{getStatusCount('bunting')}</span>
          <span style={{ fontSize: '11px', color: 'var(--text-muted)' }}>Ekor sapi terkonfirmasi</span>
        </div>
        <div className="card" style={{ margin: 0, padding: '16px', display: 'flex', flexDirection: 'column', gap: '4px' }}>
          <span className="badge badge-ib1" style={{ alignSelf: 'flex-start' }}>IB Aktif (Straw)</span>
          <span style={{ fontSize: '24px', fontWeight: '700', color: 'var(--text-main)' }}>
            {parseCount(getStatusCount('ib1')) + parseCount(getStatusCount('ib2')) + parseCount(getStatusCount('ib3'))}
          </span>
          <span style={{ fontSize: '11px', color: 'var(--text-muted)' }}>Menunggu masa kebuntingan</span>
        </div>
      </div>

      {/* 4. Directory Swiper Section */}
      <div style={{ marginBottom: '24px' }}>
        <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center', marginBottom: '12px' }}>
          <h3 className="header-title" style={{ fontSize: '16px', display: 'flex', alignItems: 'center', gap: '8px' }}>
            <BookOpen size={16} style={{ color: 'var(--primary)' }} /> Petunjuk & Panduan
          </h3>
          <span style={{ fontSize: '12px', color: 'var(--text-muted)' }}>Geser &rsaquo;</span>
        </div>
        
        {direktori && direktori.length > 0 ? (
          <div className="horizontal-swiper">
            {direktori.map((dir) => (
              <div
                key={dir.id_direktori}
                className="swiper-item"
                onClick={() => setSelectedDir(dir)}
                style={{ cursor: 'pointer', width: '150px' }}
              >
                <img
                  src={resolvePhoto(dir.cover, 'directory')}
                  alt={dir.nama}
                  onError={(e) => {
                    (e.target as HTMLImageElement).src = 'https://images.unsplash.com/photo-1500937386664-56d1dfef3854?w=150';
                  }}
                />
                <h4 style={{ fontSize: '12px', fontWeight: '600', color: 'var(--text-main)', textOverflow: 'ellipsis', overflow: 'hidden', whiteSpace: 'nowrap' }}>
                  {dir.nama}
                </h4>
                <span style={{ fontSize: '10px', color: 'var(--text-muted)', display: 'block', marginTop: '2px' }}>
                  Lihat Detail &rarr;
                </span>
              </div>
            ))}
          </div>
        ) : (
          <div className="card" style={{ padding: '16px', textAlign: 'center', color: 'var(--text-muted)', fontSize: '13px' }}>
            Belum ada panduan tersedia.
          </div>
        )}
      </div>

      {/* 5. Breeds Swiper Section */}
      <div style={{ marginBottom: '24px' }}>
        <div style={{ display: 'flex', justifySelf: 'space-between', justifyItems: 'center', justifyContent: 'space-between', marginBottom: '12px' }}>
          <h3 className="header-title" style={{ fontSize: '16px', display: 'flex', alignItems: 'center', gap: '8px' }}>
            <Award size={16} style={{ color: 'var(--accent)' }} /> Rekomendasi Bangsa Sapi
          </h3>
          <span style={{ fontSize: '12px', color: 'var(--text-muted)' }}>Geser &rsaquo;</span>
        </div>

        {bangsa && bangsa.length > 0 ? (
          <div className="horizontal-swiper">
            {bangsa.map((b) => (
              <div key={b.id_bangsa} className="swiper-item" style={{ width: '130px', padding: '16px 12px' }}>
                <div style={{
                  width: '48px',
                  height: '48px',
                  borderRadius: '50%',
                  background: 'rgba(255, 255, 255, 0.03)',
                  border: '1px solid rgba(255, 255, 255, 0.08)',
                  display: 'flex',
                  alignItems: 'center',
                  justifyContent: 'center',
                  margin: '0 auto 10px auto'
                }}>
                  <span style={{ fontSize: '18px' }}>🐄</span>
                </div>
                <h4 style={{ fontSize: '12px', fontWeight: '600', color: 'var(--text-main)' }}>
                  {b.nama_bangsa}
                </h4>
                <span style={{ fontSize: '10px', color: 'var(--primary)', display: 'block', marginTop: '4px', fontWeight: '500' }}>
                  Pedaging Unggul
                </span>
              </div>
            ))}
          </div>
        ) : (
          <div className="card" style={{ padding: '16px', textAlign: 'center', color: 'var(--text-muted)', fontSize: '13px' }}>
            Data bangsa sapi tidak ditemukan.
          </div>
        )}
      </div>

      {/* 6. Consultants List */}
      <div style={{ marginBottom: '24px' }}>
        <h3 className="header-title" style={{ fontSize: '16px', marginBottom: '12px', display: 'flex', alignItems: 'center', gap: '8px' }}>
          <User size={16} style={{ color: 'var(--primary)' }} /> Pendamping & Konsultan Ahli
        </h3>

        {konsultan && konsultan.length > 0 ? (
          <div>
            {konsultan.map((k) => (
              <div key={k.id_konsultan} className="card" style={{ display: 'flex', alignItems: 'center', gap: '16px' }}>
                <img
                  src={resolvePhoto(k.foto, 'consultant')}
                  alt={k.nama}
                  style={{ width: '48px', height: '48px', borderRadius: '50%', objectFit: 'cover', border: '1px solid rgba(255,255,255,0.08)' }}
                  onError={(e) => {
                    (e.target as HTMLImageElement).src = 'https://images.unsplash.com/photo-1622253692010-333f2da6031d?w=150';
                  }}
                />
                <div style={{ flex: 1 }}>
                  <h4 style={{ fontSize: '14px', fontWeight: '600', color: 'var(--text-main)' }}>{k.nama}</h4>
                  <p style={{ fontSize: '11px', color: 'var(--text-sub)', marginTop: '2px' }}>{k.keahlian}</p>
                </div>
                <a
                  href={`https://wa.me/${(k.telp || '').replace(/[^0-9]/g, '')}`}
                  target="_blank"
                  rel="noopener noreferrer"
                  style={{
                    background: 'rgba(16, 185, 129, 0.15)',
                    color: '#34d399',
                    border: '1px solid rgba(16, 185, 129, 0.2)',
                    width: '36px',
                    height: '36px',
                    borderRadius: '50%',
                    display: 'flex',
                    alignItems: 'center',
                    justifyContent: 'center',
                    cursor: 'pointer',
                    transition: 'var(--transition)'
                  }}
                  title="Hubungi WhatsApp"
                >
                  <MessageSquare size={16} />
                </a>
              </div>
            ))}
          </div>
        ) : (
          <div className="card" style={{ padding: '16px', textAlign: 'center', color: 'var(--text-muted)', fontSize: '13px' }}>
            Belum ada konsultan pendamping.
          </div>
        )}
      </div>

      {/* 7. Floating Action Button (Navigate to Add Cow Form) */}
      <button className="fab" onClick={() => navigateTo('add-sapi')} title="Daftarkan Sapi Baru">
        <Plus size={24} />
      </button>

      {/* Directory Detail Drawer Modal */}
      {selectedDir && (
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
          <div className="card card-glow animate-fade-in" style={{
            width: '100%',
            maxWidth: '450px',
            borderBottomLeftRadius: 0,
            borderBottomRightRadius: 0,
            borderTopLeftRadius: '24px',
            borderTopRightRadius: '24px',
            margin: 0,
            padding: '24px 20px',
            maxHeight: '80%',
            overflowY: 'auto',
            background: 'var(--bg-gradient)'
          }}>
            <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center', marginBottom: '16px' }}>
              <h3 className="header-title" style={{ fontSize: '18px' }}>{selectedDir.nama}</h3>
              <button
                onClick={() => setSelectedDir(null)}
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
            
            <img
              src={resolvePhoto(selectedDir.cover, 'directory')}
              alt={selectedDir.nama}
              style={{ width: '100%', height: '180px', objectFit: 'cover', borderRadius: '12px', marginBottom: '16px' }}
              onError={(e) => {
                (e.target as HTMLImageElement).src = 'https://images.unsplash.com/photo-1500937386664-56d1dfef3854?w=300';
              }}
            />

            <div
              style={{ fontSize: '13px', color: 'var(--text-sub)', lineHeight: '1.6' }}
              dangerouslySetInnerHTML={{
                __html: selectedDir.informasi || `Panduan detail mengenai budidaya, nutrisi, dan manajemen perawatan untuk <strong>${selectedDir.nama}</strong> tidak tersedia.`
              }}
            />

            {selectedDir.galeri && selectedDir.galeri.length > 0 && (
              <div style={{ marginTop: '20px' }}>
                <h4 style={{ fontSize: '12px', fontWeight: '600', color: 'var(--text-main)', marginBottom: '8px' }}>Galeri Foto</h4>
                <div style={{ display: 'flex', gap: '8px', overflowX: 'auto', paddingBottom: '8px' }}>
                  {selectedDir.galeri.map((g: any) => (
                    <img
                      key={g.id_galeri}
                      src={resolvePhoto(g.file, 'directory')}
                      alt="Gallery Item"
                      style={{ width: '80px', height: '60px', borderRadius: '6px', objectFit: 'cover', flexShrink: 0 }}
                      onError={(e) => {
                        (e.target as HTMLImageElement).src = 'https://images.unsplash.com/photo-1500937386664-56d1dfef3854?w=150';
                      }}
                    />
                  ))}
                </div>
              </div>
            )}
          </div>
        </div>
      )}
    </div>
  );
};
