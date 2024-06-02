// Example model schema from the Drizzle docs
// https://orm.drizzle.team/docs/sql-schema-declaration

import { sql } from 'drizzle-orm';
import {
  index,
  pgTableCreator,
  serial,
  timestamp,
  uuid,
  varchar,
} from 'drizzle-orm/pg-core';

/**
 * This is an example of how to use the multi-project schema feature of Drizzle ORM. Use the same
 * database instance for multiple projects.
 *
 * @see https://orm.drizzle.team/docs/goodies#multi-project-schema
 */
export const createTable = pgTableCreator((name) => `vacation-club_${name}`);

export const posts = createTable(
  'post',
  {
    id: serial('id').primaryKey(),
    name: varchar('name', { length: 256 }),
    createdAt: timestamp('created_at', { withTimezone: true })
      .default(sql`CURRENT_TIMESTAMP`)
      .notNull(),
    updatedAt: timestamp('updatedAt', { withTimezone: true }),
  },
  (example) => ({
    nameIndex: index('name_idx').on(example.name),
  }),
);

export const users = createTable(
  'users',
  {
    id: uuid('id').primaryKey().defaultRandom(),
    email: varchar('email', { length: 256 }).unique().notNull(),
    name: varchar('name', { length: 256 }),
    hashedPassword: varchar('hashed_password', { length: 256 }).notNull(),
    createdAt: timestamp('created_at').defaultNow().notNull(),
    updatedAt: timestamp('updated_at').$onUpdate(() => new Date()),
    deletedAt: timestamp('deleted_at'),
  },
  (table) => ({
    emailIndex: index('email_idx').on(table.email),
  }),
);

export type User = typeof users.$inferSelect;
export type CreateUserRequest = typeof users.$inferInsert;

export const sessions = createTable(
  'sessions',
  {
    id: uuid('id').primaryKey(),
    userId: uuid('user_id').notNull(),
    expiresAt: timestamp('expires_at').notNull(),
    createdAt: timestamp('created_at').defaultNow().notNull(),
    updatedAt: timestamp('updated_at').$onUpdate(() => new Date()),
    deletedAt: timestamp('deleted_at'),
  },
  (table) => ({
    userIdIndex: index('session_user_id_idx').on(table.userId),
  }),
);
