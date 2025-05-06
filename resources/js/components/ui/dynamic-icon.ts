import {
  SquareCode,
  Github,
  Twitter,
  Instagram,
  Linkedin,
  Facebook,
  Youtube,
  Link,
  Mail,
  Phone,
  MessageSquare,
  Globe,
  Music,
  Video,
  Camera,
  Store,
  ShoppingBag,
  CreditCard,
  Wallet,
  Calendar,
  Map,
  MapPin,
  FileText,
  BookOpen,
  BookMarked,
  Rss,
  Newspaper,
  Podcast,
  Heart,
  Star,
  Award,
  Briefcase,
  GraduationCap,
  Share2,
  MessageCircle,
  MessageCircle as DiscordIcon,
  Paintbrush as BehanceIcon,
  Image as PinterestIcon,
  Video as TikTokIcon,
  Twitch,
  Slack,
  Figma,
  Dribbble,
  X,
  User,
  Smartphone,
  QrCode,
  WheatOff, // para Gluten-Free
  Leaf, // para Vegan
  Coffee,
  Utensils,
  type LucideIcon,
} from 'lucide-vue-next';

export const iconMap: Record<string, LucideIcon> = {
  // Redes sociais
  github: Github,
  twitter: Twitter,
  x: X,
  instagram: Instagram,
  linkedin: Linkedin,
  facebook: Facebook,
  youtube: Youtube,
  tiktok: TikTokIcon,
  pinterest: PinterestIcon,
  twitch: Twitch,
  slack: Slack,
  discord: DiscordIcon,
  dribbble: Dribbble,
  behance: BehanceIcon,
  figma: Figma,

  // Comunicação
  mail: Mail,
  email: Mail,
  phone: Phone,
  whatsapp: MessageSquare,
  telegram: MessageCircle,
  chat: MessageCircle,

  // Web
  link: Link,
  website: Globe,
  web: Globe,
  globe: Globe,
  url: Link,
  code: SquareCode,
  qrcode: QrCode,

  // Mídia
  music: Music,
  spotify: Music,
  video: Video,
  youtube_music: Music,
  photo: Camera,
  camera: Camera,

  // Compras
  store: Store,
  shop: Store,
  shopping: ShoppingBag,
  ecommerce: Store,
  payment: CreditCard,
  wallet: Wallet,

  // Ferramentas/Utilidades
  calendar: Calendar,
  agenda: Calendar,
  map: Map,
  location: MapPin,
  documento: FileText,
  file: FileText,
  document: FileText,
  book: BookOpen,
  bookmark: BookMarked,
  blog: Newspaper,
  rss: Rss,
  news: Newspaper,
  podcast: Podcast,

  // Decorativos
  heart: Heart,
  star: Star,
  award: Award,
  premium: Award,

  // Profissional
  portfolio: Briefcase,
  work: Briefcase,
  job: Briefcase,
  career: Briefcase,
  education: GraduationCap,
  school: GraduationCap,
  university: GraduationCap,
  share: Share2,

  // Pessoal
  profile: User,
  bio: User,
  about: User,

  // Mobile
  app: Smartphone,
  mobile: Smartphone,

  // Alimentos
  glutenfree: WheatOff,
  vegan: Leaf,
  food: Utensils,
  restaurant: Utensils,
  coffee: Coffee,
  cafe: Coffee,
};

export type IconName = keyof typeof iconMap;

export function getIcon(name: string): LucideIcon {
  return iconMap[name.toLowerCase() as IconName] || Link;
}