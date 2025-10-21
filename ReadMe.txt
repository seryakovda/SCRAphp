--===================================================================
-- Таблица для триггер для отлавливания обытыи й по проходам
--===================================================================

DROP TABLE IF EXISTS [dbo].[TRIGGER_pLogData]
GO

CREATE TABLE [dbo].[TRIGGER_pLogData](
	[id] [int] IDENTITY(1,1) NOT NULL,
	[DoorIndex] [int] NOT NULL,
	[id_pLogData]
	[dt] [datetime] NULL,
 CONSTRAINT [PK_TRIGGER_pLogData] PRIMARY KEY CLUSTERED
(
	[id] ASC,
	[DoorIndex] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON, OPTIMIZE_FOR_SEQUENTIAL_KEY = OFF) ON [PRIMARY]
) ON [PRIMARY]
GO

ALTER TABLE [dbo].[TRIGGER_pLogData] ADD  CONSTRAINT [DF_TRIGGER_pLogData_dt]  DEFAULT (getdate()) FOR [dt]
GO


--===================================================================
-- Таблица для триггер для отлавливания обытыи й по проходам
--===================================================================
DROP TRIGGER IF EXISTS [dbo].[TRpLogdata_copyEvent]
GO

CREATE TRIGGER [dbo].[TRpLogdata_copyEvent]
   ON  [dbo].[pLogData]
   AFTER INSERT
AS
BEGIN
	-- SET NOCOUNT ON added to prevent extra result sets from
	-- interfering with SELECT statements.
	SET NOCOUNT ON;

	insert into TRIGGER_pLogData (DoorIndex)
	select isnull(DoorIndex,0) from inserted
    -- Insert statements for trigger here

END


--===================================================================
-- Таблицы для отслеживания изменений для постов
--===================================================================
DROP TABLE IF EXISTS [dbo].[TRIGGER_pMark_UPD]
GO

CREATE TABLE [dbo].[TRIGGER_pMark_UPD](
	[id] [int] NOT NULL,
	[id_Table] [int] NULL,
 CONSTRAINT [PK_TRIGGER_pMark_UPD] PRIMARY KEY CLUSTERED
(
	[id] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON, OPTIMIZE_FOR_SEQUENTIAL_KEY = OFF) ON [PRIMARY]
) ON [PRIMARY]
GO


DROP TABLE IF EXISTS [dbo].[TRIGGER_pList_UPD]

GO

CREATE TABLE [dbo].[TRIGGER_pList_UPD](
	[id] [int]  NOT NULL,
	[id_Table] [int] NULL,
 CONSTRAINT [PK_TRIGGER_pList_UPD] PRIMARY KEY CLUSTERED
(
	[id] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON, OPTIMIZE_FOR_SEQUENTIAL_KEY = OFF) ON [PRIMARY]
) ON [PRIMARY]
GO

--===================================================================
-- Триггеры для Таблицы для отслеживания изменений для постов
--===================================================================
DROP TRIGGER IF EXISTS [dbo].[TR_pMark_afterUpdOrInsert]
GO

CREATE TRIGGER [dbo].[TR_pMark_afterUpdOrInsert]
   ON  [dbo].[pMark]
   AFTER INSERT,UPDATE
     NOT FOR REPLICATION
AS
BEGIN
	-- SET NOCOUNT ON added to prevent extra result sets from
	-- interfering with SELECT statements.
	SET NOCOUNT ON;

    declare @id_max int
    select  @id_max  = isnull(MAX (id),0)+1 from TRIGGER_pMark_UPD
		begin TRY
			insert into TRIGGER_pMark_UPD (id,id_Table)
			select @id_max,max(isnull(ID,0)) from inserted
		end TRY
		begin CATCH
			THROW
		end CATCH

END

DROP TRIGGER IF EXISTS [dbo].[TR_pList_afterUpdOrInsert]
GO

CREATE TRIGGER [dbo].[TR_pList_afterUpdOrInsert]
   ON  [dbo].[pList]
   AFTER INSERT,UPDATE
AS
BEGIN
	-- SET NOCOUNT ON added to prevent extra result sets from
	-- interfering with SELECT statements.
	SET NOCOUNT ON;
    declare @id_max int

        select  @id_max  = isnull(MAX (id),0)+1 from TRIGGER_pList_UPD
    		begin TRY
    			insert into TRIGGER_pList_UPD (id,id_Table)
    			select @id_max,max(isnull(ID,0)) from inserted
    		end TRY
    		begin CATCH
    			THROW
    		end CATCH
END

GO
--===================================================================
-- Таблица для отслеживания изменений справичников
--===================================================================
DROP TABLE IF EXISTS [dbo].[TRIGGER_SPR]
GO
CREATE TABLE [dbo].[TRIGGER_SPR](
	[id] [int] NOT NULL,
	[name_table] [varchar](50) NULL,
 CONSTRAINT [PK_TRIGGER_SPR] PRIMARY KEY CLUSTERED
(
	[id] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON, OPTIMIZE_FOR_SEQUENTIAL_KEY = OFF) ON [PRIMARY]
) ON [PRIMARY]
GO

--===================================================================
-- Триггеры для Таблицы для отслеживания изменений справичников
--===================================================================
DROP TRIGGER IF EXISTS [dbo].[TR_AcessPoint_AllChanges]
GO

CREATE TRIGGER [dbo].[TR_AcessPoint_AllChanges]
   ON  [dbo].[AcessPoint]
   AFTER INSERT,UPDATE,DELETE
AS
BEGIN
	-- SET NOCOUNT ON added to prevent extra result sets from
	-- interfering with SELECT statements.
	SET NOCOUNT ON;

    declare @id_max int
    select  @id_max  = isnull(MAX (id),0)+1 from TRIGGER_SPR
	insert into TRIGGER_SPR (id,name_table)
	values (@id_max,'AcessPoint')
END

GO

DROP TRIGGER IF EXISTS [dbo].[TR_GrAccess_AllChanges]
GO
CREATE TRIGGER [dbo].[TR_GrAccess_AllChanges]
   ON  [dbo].[GrAccess]
   AFTER INSERT,UPDATE,DELETE
AS
BEGIN
	-- SET NOCOUNT ON added to prevent extra result sets from
	-- interfering with SELECT statements.
	SET NOCOUNT ON;

    declare @id_max int
    select  @id_max  = isnull(MAX (id),0)+1 from TRIGGER_SPR
	insert into TRIGGER_SPR (id,name_table)
	values (@id_max,'GrAccess')
END

GO
--===================================================================
-- Представление отражения pList
--===================================================================

DROP VIEW IF EXISTS [dbo].[View_pList]
GO

CREATE VIEW [dbo].[View_pList]
    AS
    SELECT
        dbo.pList.ID,
        dbo.pList.Name,
        dbo.pList.FirstName,
        dbo.pList.MidName,
        dbo.pList.Status,
        dbo.pList.Picture,
        dbo.pList.BirthDate,
        dbo.pList.Company,
        dbo.pList.Section,
        dbo.pList.Post,
        dbo.pList.TabNumber,
        dbo.PCompany.Name AS name_PCompany,
        dbo.PPost.Name AS name_PPost,
        dbo.PDivision.Name AS name_PDivision,
        dbo.pList.status_list
    FROM
        dbo.pList
    INNER JOIN
        dbo.PCompany
            ON
                dbo.pList.Company = dbo.PCompany.ID
    INNER JOIN
        dbo.PPost
            ON
                dbo.pList.Post = dbo.PPost.ID
    INNER JOIN
        dbo.PDivision
            ON
            dbo.pList.Section = dbo.PDivision.ID
GO


--===================================================================
-- Процедура срабатывает когда появляется событие по проходу
--===================================================================
DROP PROCEDURE IF EXISTS Proc_reactionToTheEvent
GO

CREATE PROCEDURE Proc_reactionToTheEvent
                 @_DoorIndex varchar (50) = ''
                AS
                BEGIN
                    -- SET NOCOUNT ON added to prevent extra result sets from
                    -- interfering with SELECT statements.
                    declare @f int
                    declare @count int
                    SET NOCOUNT ON;
                    -- Удалаем свои события
                    delete [dbo].TRIGGER_pLogData where  DoorIndex in  (SELECT convert(int,value) FROM STRING_SPLIT(@_DoorIndex, ','))

                    -- проверяем сколько событий неакопилось в общем
                    set @f = (select isnull((SELECT         sum(1) FROM            [dbo].TRIGGER_pLogData),0))
                    if @f > 50 -- в слкчае если больше 50 то чистим таблицу
                        delete [dbo].TRIGGER_pLogData
                    set @f = 0
                    set @count = 0
                    while @f = 0
                        begin
                            waitfor DELAY '00:00:00.333'
                            set @f = (
                                select  isnull(
                                    (
                                        SELECT
                                               sum(1)
                                        FROM
                                             [dbo].TRIGGER_pLogData
                                        WHERE
                                              (
                                                  DoorIndex in  (SELECT convert(int,value) FROM STRING_SPLIT(@_DoorIndex, ','))
                                              )
                                    )
                                    ,0)
                                )
                            set @count = @count + 1
                            if (@count > 200 )
                                set @f = 1
                        END
                end