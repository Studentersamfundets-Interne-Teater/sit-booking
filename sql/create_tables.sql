CREATE EXTENSION IF NOT EXISTS "uuid-ossp";

CREATE TYPE bookingstatus AS ENUM ('approved', 'pending', 'rejected');

CREATE TABLE IF NOT EXISTS user_account (
  username text PRIMARY KEY,
  fullname text NOT NULL,
  email text NOT NULL,
  phone text NOT NULL
);

CREATE TABLE IF NOT EXISTS booking (
  id uuid PRIMARY KEY DEFAULT uuid_generate_v4(),
  username text NOT NULL REFERENCES user_account ON DELETE CASCADE,
  title text NOT NULL,
  description text,
  starttime timestamp NOT NULL,
  endtime timestamp NOT NULL,
  status bookingstatus NOT NULL DEFAULT 'pending',
  CONSTRAINT valid_times CHECK(endtime > starttime)
);