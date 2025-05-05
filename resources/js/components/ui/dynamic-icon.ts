import {
  SquareCode,
  Github,
  Twitter,
  Instagram,
  Linkedin,
  Facebook,
  Youtube,
  Link,
  type LucideIcon,
} from 'lucide-vue-next';

export const iconMap: Record<string, LucideIcon> = {
  github: Github,
  twitter: Twitter,
  instagram: Instagram,
  linkedin: Linkedin,
  facebook: Facebook,
  youtube: Youtube,
  code: SquareCode,
  link: Link,
};

export type IconName = keyof typeof iconMap;

export function getIcon(name: string): LucideIcon {
  return iconMap[name.toLowerCase() as IconName] || Link;
} 