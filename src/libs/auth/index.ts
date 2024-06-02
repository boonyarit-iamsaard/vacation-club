import { webcrypto } from 'node:crypto';

import { DrizzlePostgreSQLAdapter } from '@lucia-auth/adapter-drizzle';
import { Lucia, TimeSpan } from 'lucia';

import { db } from '~/server/db';
import { sessions, users } from '~/server/db/schema';
import type { User as DatabaseUser } from '~/server/db/schema';
import { env } from '~/env';

// for web crypto api support on node.js 18 or lower
globalThis.crypto = webcrypto as Crypto;

const adapter = new DrizzlePostgreSQLAdapter(db, sessions, users);

export const lucia = new Lucia(adapter, {
  getSessionAttributes: () => ({}),
  getUserAttributes: (attributes) => ({
    id: attributes.id,
    email: attributes.email,
    name: attributes.name,
    createdAt: attributes.createdAt,
    updatedAt: attributes.updatedAt,
    deletedAt: attributes.deletedAt,
  }),
  sessionCookie: {
    name: 'session',
    expires: false,
    attributes: {
      secure: env.NODE_ENV === 'production',
    },
  },
  sessionExpiresIn: new TimeSpan(30, 'd'),
});

declare module 'lucia' {
  interface Register {
    Lucia: typeof lucia;
    DatabaseSessionAttributes: DatabaseSessionAttributes;
    DatabaseUserAttributes: DatabaseUserAttributes;
  }
}

// TODO: try improving this type definition
// eslint-disable-next-line @typescript-eslint/no-empty-interface
interface DatabaseSessionAttributes {}
// eslint-disable-next-line @typescript-eslint/no-empty-interface
interface DatabaseUserAttributes extends Omit<DatabaseUser, 'hashedPassword'> {}
