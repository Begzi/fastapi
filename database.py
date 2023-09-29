from sqlalchemy import create_engine

from config import DB_HOST, DB_USER, DB_NAME, DB_PASS, DB_PORT

engine = create_engine("postgresql+psycopg2://" + DB_USER + ":" + DB_PASS + "@" + DB_HOST + ":" + DB_PORT + "/" + DB_NAME)

engine.connect()