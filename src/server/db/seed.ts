import { webcrypto } from 'node:crypto';

import * as dotenv from 'dotenv';
import { drizzle } from 'drizzle-orm/postgres-js';
import { Scrypt } from 'lucia';
import postgres from 'postgres';
import { z } from 'zod';

import { posts, users } from '~/server/db/schema';

// for web crypto api support on node.js 18 or lower
globalThis.crypto = webcrypto as Crypto;

dotenv.config({ path: '.env' });

const url = z.string().url().parse(process.env.DATABASE_URL);

async function main() {
  const client = postgres(url, { max: 1 });
  const db = drizzle(client, { schema: { users, posts } });

  try {
    console.log('⌛ Seeding database...');

    // eslint-disable-next-line drizzle/enforce-delete-with-where
    await db.delete(users);
    // eslint-disable-next-line drizzle/enforce-delete-with-where
    await db.delete(posts);

    const hashedPassword = await new Scrypt().hash('winteriscoming');
    await db.insert(users).values([
      {
        email: 'arya@stark.com',
        name: 'Arya Stark',
        hashedPassword,
      },
    ]);

    console.log('✅ Database seeded successfully');
  } catch (error) {
    throw error;
  }
}

// TODO: consider wether using process.exit() is necessary or not
main()
  .catch((error) => {
    console.error('❌ Seeding failed', error);
    process.exit(1);
  })
  .finally(() => {
    process.exit();
  });
